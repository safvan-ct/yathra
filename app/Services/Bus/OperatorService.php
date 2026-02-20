<?php
namespace App\Services\Bus;

use App\Models\Operator;

class OperatorService
{
    public function find($where)
    {
        return Operator::where($where)->first();
    }

    public function findOrFail($id)
    {
        return Operator::findOrFail($id);
    }

    public function dataTable()
    {
        return Operator::select('id', 'name', 'phone', 'type', 'is_active', 'auth_status');
    }

    public function store($data)
    {
        return Operator::create($data);
    }

    public function update($id, $data)
    {
        $operator = $this->findOrFail($id);

        return $operator->update($data);
    }

    public function search($search)
    {
        return Operator::select('id', 'name', 'phone')->where('name', 'LIKE', "%$search%")->limit(20)->get();
    }
}
