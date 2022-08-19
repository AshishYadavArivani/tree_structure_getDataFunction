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
                
                // echo gettype($array_child_data);
                $spc=60;
                echo "<div style='border:1px solid aqua;width: fit-content;margin-left:{$spc}px;text-align:left;padding:20px;box-shadow: 15px 15px 10px #888888 inset;font-size:19px;font-weight:normal;font-family:algerian'>";
                echo "<br/>";
                //if()
                echo "id =>".$array_child_data->id;
                echo "<br/>";
                echo "Name =>".$array_child_data->Name;
                echo "<br/>";
                echo " Child =>";
                
                $this->print_json($array_child_data,$spc);
                echo "</div>";
                dd($arr_data1,$json_child_data,$array_child_data);
                


        }

        public function print_json($data,$spc)
        {
          if(gettype($data->child) === 'array')
          {
          
            if(count($data->child)>0)
            { 
                    $i=0;
                    for($i=0;$i<count($data->child);$i++)
                    {  
                        echo "<div style='border:1px solid aqua;width: fit-content;margin-left:{$spc}px;text-align:left;padding:20px;box-shadow: 15px 15px 10px #888888 inset;'>";
                        
                        echo "<br/>";
                        echo "<span > id =>".$data->child[$i]->id."</span>";
                        echo "<br/>";
                        echo "<span> Name =>".$data->child[$i]->Name."</span>";
                        echo "<br/>";
                        echo "<span > Child =></span>";
                       
                        $j=0;
                    if(gettype($data->child[$i]->child) === 'array')
                    {   
                       
                        $j=60; 
                        $spc=($spc+$j);
                        $this->print_json($data->child[$i],$spc);
                        
                    }
                    $spc=($spc-$j);  
                    
                    echo "</div>";
                    }
                    
                    // echo "<span style='margin-left: 30px; padding:5px;'> id =>".$child->id."</span>";   
                }
            // }
        }
            
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
