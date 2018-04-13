<?php

namespace crocodicstudio\crudbooster\Modules\ModuleGenerator;

use crocodicstudio\crudbooster\helpers\DbInspector;

class Step2Handler
{
    public function showForm($id)
    {
        $module = ModulesRepo::find($id);

        $columns = \Schema::getColumnListing($module->table_name);

        $controllerCode = (FileManipulator::readCtrlContent($module->controller));

        $data = [];
        $data['id'] = $id;
        $data['columns'] = $columns;
        //$data['table_list'] = \crocodicstudio\crudbooster\helpers\CRUDBooster::listCbTables();
        $data['cols'] = ScaffoldingParser::parse($controllerCode, 'col');


        /*foreach($this->hooks as $hook){
            $data[$hook] = FileManipulator::readMethodContent($controllerCode, $hook);
        }*/

        return view('CbModulesGen::step2', $data);
    }

    public function handleFormSubmit()
    {
        $id = request('id');
        $controller = ModulesRepo::getControllerName($id);
        $newCode = PhpColConfig::makeColumnPhpCode();
        $code = FileManipulator::readCtrlContent($controller);
        $fileResult = FileManipulator::replaceBetweenMark($code, 'COLUMNS', $newCode);

        /* foreach($this->hooks as $hook){
            $fileResult = FileManipulator::writeMethodContent($fileResult, $hook, request($hook));
        }*/

        FileManipulator::putCtrlContent($controller, $fileResult);

        return redirect()->route("AdminModulesControllerGetStep3", ["id" => $id]);
    }
}