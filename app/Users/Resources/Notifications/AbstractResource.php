<?php

namespace App\Users\Resources\Notifications;

use App\Enums\NotificationProviderEnum;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ExpectedValues;

abstract class AbstractResource
{
    /**
     * @var array
     */
    protected array $fields = [];

    /**
     * @return \App\Enums\NotificationProviderEnum
     */
    abstract public function getProvider(): NotificationProviderEnum;

    /**
     * @return void
     */
    abstract public function fields(): void;

    /**
     * @param string $column
     * @param string $label
     * @param bool $required
     *
     * @return void
     */
    protected function addStringField(string $column, string $label, bool $required = true): void
    {
        $this->input($column, $label, $required);
    }

    /**
     * @param string $column
     * @param string $label
     * @param bool $required
     *
     * @return void
     */
    protected function addPasswordField(string $column, string $label, bool $required = true): void
    {
        $this->input($column, $label, $required, 'password');
    }

    /**
     * @param string $column
     * @param string|null $label
     * @param bool $required
     *
     * @return void
     */
    protected function addEmailField(string $column, ?string $label = null, bool $required = true): void
    {
        $this->input($column, $label, $required, 'email');
    }

    /**
     * @param string $column
     * @param string|null $label
     * @param bool $required
     *
     * @return void
     */
    protected function addCheckboxField(string $column, ?string $label = null, bool $required = true): void
    {
        $this->input($column, $label, $required, 'checkbox');
    }

    /**
     * @param string $column
     * @param string|null $label
     * @param bool $required
     * @param string $type
     *
     * @return void
     */
    protected function input(
        string $column,
        ?string $label = null,
        bool $required = true,
        #[ExpectedValues(values: ['text', 'password', 'checkbox', 'color', 'email', 'checkbox', 'tel'])]
        string $type = 'text'
    ): void {
        if (!$label) {
            $label = Str::ucfirst($column);
        }

        $this->fields[] = [
            'component' => 'input',
            'column' => $column,
            'label' => $label,
            'required' => $required,
            'type' => $type,
        ];
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        $this->fields();

        return $this->fields;
    }
}
