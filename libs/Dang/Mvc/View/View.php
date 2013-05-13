<?php

class Dang_Mvc_View_View
{
    public function render(Dang_Mvc_View_Model_ModelInterface $model)
    {
        if ($model->hasChildren()) {
            $this->renderChildren($model);
        }

        $renderer = new Dang_Mvc_PhpRenderer();
        $content = $renderer->renderModel($model);

        return $content;
    }

    protected function renderChildren(Dang_Mvc_View_Model_ModelInterface $model)
    {
        foreach ($model->getChildren() as $child)
        {
            $content  = $this->render($child);
            $capture = $child->captureTo();
            if (!empty($capture)) {
                $model->setVariable($capture, $content);
            }
        }
    }

}