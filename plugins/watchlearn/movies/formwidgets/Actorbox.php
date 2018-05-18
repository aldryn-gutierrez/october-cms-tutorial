<?php namespace Watchlearn\Movies\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Config;
use Watchlearn\Movies\Models\Actor;


class ActorBox extends FormWidgetBase
{
    public function widgetDetails()
    {
        return [
            'name' => 'Actorbox',
            'description' => 'Field for adding actors',
        ];
    }

    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('widget');
    }

    public function prepareVars()
    {
        $this->vars['id'] = $this->model->id;
        $this->vars['actors'] = Actor::all()->lists('fullname', 'id');
        $this->vars['name'] = $this->formField->getName().'[]';
        $this->vars['selectedValues'] = $this->getLoadValue() ?? [];
    }

    public function loadAssets()
    {
        $this->addCss('css/select2.css');
        $this->addJs('js/select2.js');
    }

    public function getSaveValue($actors)
    {
        $newArray = [];

        if ($actors) {
            foreach ($actors as $actorId) {
                if (!is_numeric($actorId)) {
                    $fullnamePieces = explode(' ', $actorId);

                    $newActor = new Actor();                
                    $newActor->name = $fullnamePieces[0] ?? '';
                    $newActor->lastname = $fullnamePieces[1] ?? '';
                    $newActor->save();

                    $actorId = $newActor->id;
                } 
                
                $newArray[] = $actorId;
            }
        }

        return $newArray;
    }
}
