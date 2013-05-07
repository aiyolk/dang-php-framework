<?php

class Mvc_View_View
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * Set MVC request object
     *
     * @param  Request $request
     * @return View
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Set MVC response object
     *
     * @param  Response $response
     * @return View
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * Get MVC request object
     *
     * @return null|Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get MVC response object
     *
     * @return null|Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Render the provided model.
     *
     * Internally, the following workflow is used:
     *
     * - Trigger the "renderer" event to select a renderer.
     * - Call the selected renderer with the provided Model
     * - Trigger the "response" event
     *
     * @triggers renderer(ViewEvent)
     * @triggers response(ViewEvent)
     * @param  Model $model
     * @throws Exception\RuntimeException
     * @return void
     */
    public function render(Mvc_Model_ModelInterface $model)
    {
        // If we have children, render them first, but only if:
        // a) the renderer does not implement TreeRendererInterface, or
        // b) it does, but canRenderTrees() returns false
        if ($model->hasChildren()) {
            $this->renderChildren($model);
        }

        $renderer = new Mvc_PhpRenderer();
        $content = $renderer->renderModel($model);

        return $content;
    }

    /**
     * Loop through children, rendering each
     *
     * @param  Model $model
     * @throws Exception\DomainException
     * @return void
     */
    protected function renderChildren(Mvc_Model_ModelInterface $model)
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