<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{ 

    // == Table Structure  of Database ==========>
      /*  id  | Name    | child_id
           1  | ram     | 2
           2  | shyam   |3
           3 | abc      |0
      
      */
    // == /Table Structure  of Database ==========>

//====================================== MAIN FUNCTION FOR GETING DATA FROM DATABASE IN TREE STRUCTURE ========================================================================>  

        // Index Function for accepting First Parent id= $id
        public function index()
        {
                $data=DB::table('multi_childs')->where('id',1)->get()->first();
                $arr_data1=['id'=>$data->id,'Name'=>$data->name,"child"=>''];
                if($data->pid != '')
                {
                    $pidData=explode(',',$data->pid);
                    $multi_data=[];
                    for($i=0;$i<count($pidData);$i++)
                    {
                        array_push($multi_data,$this->get_child($pidData[$i]));
                    }
                    if($multi_data[0] == null )
                    {
                    $multi_data='';
                    }
                    $arr_data1['child']=$multi_data;
                }else
                {
                    $arr_data1['child']='';
                }
                $json_child_data=json_encode($arr_data1);
                $array_child_data=json_decode($json_child_data);
                dd($arr_data1,$json_child_data,$array_child_data);
        }
    
//====================================== /MAIN FUNCTION FOR GETING DATA FROM DATABASE IN TREE STRUCTURE ========================================================================>  

//--------------------------------------------------------------------------------------------------------------------------------------

//====================================== RECURSIVE FUNCTION FOR GETING DATA FROM DATABASE IN TREE STRUCTURE ========================================================================>  
        
        //Recursive Function aceepting child_id as parameter id=$id
        public function get_child($id)
        {
            if(DB::table('multi_childs')->where('id',$id)->exists())
            {
                if($id != '' and  $id != 0 and $id != null)
                {
                    try
                    {
                        $data2=DB::table('multi_childs')->where('id',$id)->get()->first();
                    }
                    catch(Exception  $e)
                    {
                        $data2->id='';
                        $data2->name='';
                        $data2->pid ='';
                    }

                    $arr=['id'=>$data2->id,'Name'=>$data2->name,"child"=>''];
                    $multi_arr2=[];
                    if($data2->pid != '')
                    {
                        $pidData2=explode(',',$data2->pid);

                        for($i=0;$i<count($pidData2);$i++)
                        {
                            array_push($multi_arr2,$this->get_child($pidData2[$i]));
                        }
                    
                        if($multi_arr2[0] == null )
                        {
                        echo "null data";
                        $multi_arr2='';
                        }

                        $arr['child']=$multi_arr2;
                    }else
                    {
                        $arr['child']='';
                    }

                    return $arr;
                }else
                {
                    return;
                }
            }else
            {
                return;
            }

        }
//====================================== / RECURSIVE FUNCTION FOR GETING DATA FROM DATABASE IN TREE STRUCTURE ========================================================================>  

}
