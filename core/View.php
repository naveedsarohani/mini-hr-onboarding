<?php

namespace Core;

class View
{
    private $template;
    private $data;

    public function __construct(string $template, array $data)
    {
        $this->template = $template;
        $this->data = $data;
    }

    public function with(string $key, string|array|object $value): View
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function __toString()
    {
        $template = view_path(str_replace('.', '/', $this->template) . '.php');

        if (!file_exists($template)) {
            die("The template <strong>{$template}</strong> not found.");
        }

        !empty($this->data) && extract($this->data);
        ob_start();

        include_once $template;
        return ob_get_clean();
    }
}
