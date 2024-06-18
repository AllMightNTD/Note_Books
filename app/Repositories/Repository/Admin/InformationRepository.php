<?php
namespace App\Repositories\Repository\Admin;

use App\Models\Information;
use App\Repositories\Interfaces\InformationInterface;
use Illuminate\Http\Request;

class InformationRepository implements InformationInterface {
    
    protected $information;

    public function __construct(Information $information)
    {
        $this -> information = $information;
    }
    
    public function store($data){
        return $this -> information::query() -> create($data);
    }

    public function show(Request $request , $id){
        return $this -> information::query() -> where('id' , $id);
    }


}