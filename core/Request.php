<?php

namespace Core;

use DateTime;

class Request
{
    public array|object|null $user;
    public array $headers;
    public array $form = [];
    public array $failedWithValidations = [];

    public function __construct(array $formData, ?array $files = null)
    {
        $this->user = session('authenticated_user');
        $this->headers = getallheaders() ?? [];

        foreach ($formData as $key => $value) {
            $this->form[$key] = __(trim(strip_tags($value)));
        }

        if ($files) foreach ($files as $key => $file) {
            $this->form[$key] = (object) $file;
        }
    }

    public function all()
    {
        return $this->form;
    }

    public function __get(string $key)
    {
        if (!array_key_exists($key, $this->form)) {
            return null;
        }
        return $this->form[$key];
    }

    public function put($key, $rule, ?string $message)
    {
        if (!array_key_exists($key, $this->failedWithValidations)) {
            $this->failedWithValidations[$key] = str_replace(
                ':attribute',
                strtolower($this->attributes[$key] ?? str_replace('_', ' ', $key)),
                $message ?? $this->messages[$rule]
            );
        }
    }

    public function validate(array $validations, array $messages = []): ?object
    {
        foreach ($validations as $key => $applicableRules) {
            $rules = explode('|', $applicableRules);
            foreach ($rules as $rule) $this->applyRule($key, $rule, $messages, $rules);
        }

        if (!empty($this->failedWithValidations)) {
            echo redirect()->back()->with('form-errors', $this->failedWithValidations)->withInputs();
            exit(1);
        }

        return (object) $this->form;
    }

    private function applyRule(string $key, string $rule, array $messages, $rules): void
    {
        switch ($rule) {
            case 'required':
                if (!in_array('nullable', $rules) && is_object($this->form[$key]) && $this->form[$key]->error === 4) {
                    $this->put($key, $rule, $messages[$key] ?? null);
                } elseif (!in_array('nullable', $rules) && !is_object($this->$key) && !preg_match('/\S/', $this->$key)) {
                    $this->put($key, $rule, $messages[$key] ?? null);
                }
                break;

            case 'email':
                if (!in_array('nullable', $rules) && !preg_match('/^[a-zA-Z][a-zA-Z0-9-_\+\.]*@[a-z0-9]+\.[a-z]{2,}$/', $this->$key)) {
                    $this->put($key, $rule, $messages[$key] ?? null);
                }
                break;

            case 'date':
                $date = DateTime::createFromFormat('Y-m-d', $this->$key);
                if (!in_array('nullable', $rules) && !$date || $date->format('Y-m-d') !== $this->$key) {
                    $this->put($key, $rule, $messages[$key] ?? null);
                }
                break;

            case 'string':
                if (!in_array('nullable', $rules) && !preg_match('/^[a-zA-Z\s\.\'\-]+$/', $this->$key)) {
                    $this->put($key, $rule, $messages[$key] ?? null);
                }
                break;

            case 'numeric':
                if (!in_array('nullable', $rules) && !preg_match('/^[0-9]+$/', $this->$key)) {
                    $this->put($key, $rule, $messages[$key] ?? null);
                }
                break;

            case str_starts_with($rule, 'size:'):
                $size = intval(str_replace('size:', '', $rule));

                if (!in_array('nullable', $rules) && is_object($this->form[$key]) && ($this->form[$key]->size / 1024) > $size) {
                    $this->put($key, $rule, "The :attribute file nmust not exceed $size");
                } elseif (!in_array('nullable', $rules) && !is_object($this->$key) && $this->$key > $size) {
                    $this->put($key, $rule, "The :attribute length must not exceed $size");
                }
                break;

            case str_starts_with($rule, 'mimes:'):
                $applicables = explode(',', str_replace('mimes:', '', $rule));

                $validFormat = [
                    'word' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'pdf' => 'application/pdf',
                    'jpg' => 'image/jpg',
                    'png' => 'image/png',
                    'jpeg' => 'image/jpeg'
                ];

                $allowedMimeTypes = array_map(function ($type) use ($validFormat) {
                    if (!isset($validFormat[$type])) {
                        throw new \InvalidArgumentException("Unsupported mime type: {$type}");
                    }
                    return $validFormat[$type];
                }, $applicables);

                $actualType = $this->form[$key]?->type ?? null;

                if (!in_array('nullable', $rules) && !$actualType) {
                } elseif ($actualType && !in_array($actualType, $allowedMimeTypes)) {
                    $this->put($key, $rule, "The :attribute must be of type: " . implode(', ', $applicables));
                }
                break;
        }
    }

    protected $messages = [
        'required' => 'The :attribute is required.',
        'string' => 'The :attribute should contain letters only.',
        'email' => 'The :attribute is invalid.',
        'date' => 'The :attribute contains invalid date format.',
        'numeric' => 'The :attribute should contain numbers only.',
    ];
    protected $attributes = [
        'email' => 'Email Address',
    ];
}
