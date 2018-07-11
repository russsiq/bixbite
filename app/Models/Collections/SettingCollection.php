<?php

namespace BBCMS\Models\Collections;

use Illuminate\Database\Eloquent\Collection;

class SettingCollection extends Collection
{

    /**
     * Generate a form field with parameters.
     *
     * For the overall development https://developer.mozilla.org/en-US/docs/Web/HTML/Element#Forms
     *
     * Allowed `$field['type']`:
     *      text, select, hidden, checkbox, radio, button, textarea, email, password, number, file, captcha,
     *      url, search, datetime-local
     *
     * Special `$field['type']`:
     *      * string - union: varchar, string. As <input type="text">
     *      * int - union: int, integer. As <input type="number">
     *      * float - union: number, real, float, double. As <input type="number">
     *      * enum - union: enum, radio, select. As <select></select>
     *      * boolean - union: bool, boolean, checkbox. As <input type="checkbox">
     *      * select-dirs
     *
     * Knowingly unused html-types: image, radio
     *
     * Please note to these `$field['type']`:
     *      `html` - fully line is raw html;
     *      `manual` - only input field raw html;
     *
     * $field['html_flags']:
     *      style, placeholder, onclick, required, readonly, disabled, multiple, rows, accept, size, etc., etc.
     *      Else formaction, formmethod, formenctype for type="submit"
     *
     * @return mixed
     */
    public function makeFormFields() // render ???
    {
        return $this->transform(function ($field) {
            if (empty($field->type) or empty($field->name)) {
                throw new \InvalidArgumentException('Missing required parameters for [Setting: name, type].');
            }

            // Continue processing input data. Assign default values
            $field->value = $field->value ?? null;
            $field->params = $field->params ?? [];
            $field->class = $field->class ?? 'form-control';
            $field->html_flags = $field->html_flags ?? null;

            // html: and that's enough
            if ('html' == $field->type) {
                return ['type' => 'html', 'input' => $field->value];
            }

            // Prepare specific variables with attribute parameters.
            if (in_array($field->type, ['select-lang', 'select-skins', 'select-themes'])) {
                $field->params = select_dir(app()->resourcePath(str_replace('select-', '', $field->type)));
                $field->type = 'select-with';
            } elseif ('select-fonts' == $field->type) {
                $field->params = select_file('fonts');
                $field->type = 'select-with';
            }

            // Prepare variables
            // pattern ???
            $input = '';
            $type = ' type="' . $field->type . '"';
            $name = ' name="' . $field->name . '"';
            $value = ' value="' . $field->value . '"';
            $class = ' class="' . $field->class . '" ';
            $params = $field->params;
            $html_flags = $field->html_flags;

            // dump(implode(' ', mb_split('[\s\r\n|\r|\n]+', $field->html_flags)));

            // Case - sort by popularity of use ???
            switch ($field->type) {
                case 'string':
                case 'varchar':
                    $type = ' type="text"';
                    $input = '<input'.$type.$name.$value.$class.$html_flags.'>';
                    break;

                case 'email':
                case 'password':
                case 'url':
                case 'search':
                case 'datetime-local':
                    $input = '<input'.$type.$name.$value.$class.$html_flags.'>';
                    break;

                case 'int':
                case 'integer':
                    $type = ' type="number"';
                    $input = '<input'.$type.$name.' value="'.(int) $field->value.'" step="1"'.$class.$html_flags.'>';
                    break;

                case 'real':
                case 'float':
                case 'double':
                case 'number':
                    $type = ' type="number"';
                    $step = str_contains($html_flags, 'step=') ? '' : ('step=" '.(isset($field->step) ? (float) $field->step : 'any').'" ');
                    $input = '<input'.$type.$name.' value="'.(float) $field->value.'" '.$step.$class.$html_flags.'>';
                    break;

                case 'enum':
                case 'radio':
                case 'select':
                    $input = '<select'.$name.$class.$html_flags.'>';
                    foreach ($params as $k => $v) {
                        $input .= '<option value="'.$k.'" '.($k == $field->value ? 'selected ' : '').'>'.__($v).'</option>';
                    }
                    $input .= '</select>';
                    break;

                case 'select-with':
                    $input = '<select'.$name.$class.$html_flags.'>';
                    foreach ($params as $k => $v) {
                        $input .= '<option value="'.$k.'" '.($k == $field->value ? 'selected ' : '').'>'.$v.'</option>';
                    }
                    $input .= '</select>';
                    break;

                case 'text':
                case 'textarea':
                    $input = '<textarea'.$name.$class.$html_flags.'>'.$field->value.'</textarea>';
                    break;

                // Textarea without break-line. Used javascript.
                case 'text-inline':
                    $input = '<textarea'.$name.$class.$html_flags.' rows="1" onkeydown="return !(event.keyCode == 13)"'.'>'.$field->value.'</textarea>';
                    break;

                case 'bool':
                case 'boolean':
                    $input = '<select'.$name.$class.$html_flags.'>';
                    foreach ([__('no'), __('yes')] as $k => $v) {
                        $input .= '<option value="'.$k.'" '.($k == $field->value ? 'selected ' : '').'>'.$v.'</option>';
                    }
                    $input .= '</select>';
                    break;

                case 'checkbox':
                    $type = ' type="checkbox"';
                    $input = '<input'.$type.$name.' value="1" '.((bool) $field->value ? 'checked ' : '').$html_flags.'>';
                    break;

                case 'button':
                    $class = ' class="btn '.($field->class ?? 'btn-default').'" ';
                    $input = '<input'.$type.$name.$value.$class.$html_flags.'>';
                    break;

                case 'submit':
                    $class = ' class="btn '.($field->class ?? 'btn-success').'" ';
                    $input = '<input'.$type.$name.$value.$class.$html_flags.'>';
                    break;

                case 'hidden':
                    $input = '<input'.$type.$name.$value.'>';
                    break;
                case 'captcha':
                    $input = get_captcha();
                    break;

                case 'file':
                    $input = '<div class="btn btn-default btn-secondary btn-fileinput form-control"><span><i class="fa fa-plus"></i> Select file ...</span>';
                    $input .= '<input'.$type.$name.$html_flags.'>';
                    $input .= '</div>';
                    break;

                case 'manual':
                default:
                    $input = $field->input;
                    $field->type = 'manual';
                    break;
            }

            // Output variable
            return (object) [
                'section' => $field->section ?? 'main',
                'fieldset' => $field->fieldset ?? 'general',

                'id' => ((int) $field->id) ?? null,
                'name' => $field->name,
                'type' => $field->type,

                'input' => $input,
                'title' => $field->title,
                'descr' => $field->descr,
            ];
        });
    }
}
