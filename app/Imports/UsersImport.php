<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Hash;
use Illuminate\Support\Str;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'username' => 'user_'.Str::random(10),
            'lastname' => $row['apellidos'],
            'name'     => $row['nombres'],
            'email'    => $row['email'],
            'area'     => $row['area'],
            'password' => Hash::make(Str::random(10)),
            'remember_token'     => Str::random(20),
        ]);
    }

    /*public function rules(): array
    {
        return [
            'name' => 'required|max:200',
            'lastname' => 'required|max:200',
            'email' => 'required|email|max:100',
            'area' => 'required|min:6|max:100',
        ];
    }*/
}
