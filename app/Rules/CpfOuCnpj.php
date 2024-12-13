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
            if ($cpf[$t] != $d) {
                return false;
            }
        }

        return true;
    }

    private function isCnpj(string $cnpj): bool
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);

        if (strlen($cnpj) !== 14 || preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }

        $weights1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $weights2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        // Valida o primeiro dígito verificador
        $d1 = 0;
        for ($i = 0; $i < 12; $i++) {
            $d1 += $cnpj[$i] * $weights1[$i];
        }
        $d1 = $d1 % 11;
        $d1 = $d1 < 2 ? 0 : 11 - $d1;

        if ($cnpj[12] != $d1) {
            return false;
        }

        // Valida o segundo dígito verificador
        $d2 = 0;
        for ($i = 0; $i < 13; $i++) {
            $d2 += $cnpj[$i] * $weights2[$i];
        }
        $d2 = $d2 % 11;
        $d2 = $d2 < 2 ? 0 : 11 - $d2;

        return $cnpj[13] == $d2;
    }
}
