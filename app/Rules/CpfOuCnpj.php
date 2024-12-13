<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfOuCnpj implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->isCpf($value) && !$this->isCnpj($value)) {
            $fail(__('O campo :attribute deve ser um CPF ou CNPJ válido.'));
        }
    }

    private function isCpf(string $cpf): bool
    {
        $cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    private function isCnpj(string $cnpj): bool
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);

        if (strlen($cnpj) !== 14) {
            return false;
        }

        $weight = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($t = 12; $t < 14; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cnpj[$c] * $weight[$c + 1 - (14 - $t)];
            }
            $d = $d % 11;
            $d = $d < 2 ? 0 : 11 - $d;
            if ($cnpj[$t] != $d) {
                return false;
            }
        }

        return true;
    }
}
