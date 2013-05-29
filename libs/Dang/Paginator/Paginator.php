<?php

/*
 * 分页类
 * @author wuqingcheng
 * @date 2013.04.22
 * @email wqc200@gmail.com
 */

class Dang_Paginator_Paginator
{
    /*
     * 每页默认显示的item个数
     */
    protected static $defaultItemCountPerPage = 10;
    /*
     * 分页显示的步长
     */
    protected $pageRange = 5;
    /*
     * 总页数
     */
    protected $pageCount = null;
    /*
     * 当前页
     */
    protected $currentPageNumber = 1;

    protected $totalItemCount;


    public function __construct()
    {
    }

    public function getPageRange()
    {
        return $this->pageRange;
    }

    public function setPageRange($pageRange)
    {
        $this->pageRange = (integer) $pageRange;

        return $this;
    }

    public function getTotalItemCount()
    {
        return $this->totalItemCount;
    }

    public function setTotalItemCount($totalItemCount)
    {
        $this->totalItemCount = $totalItemCount;
        return $this;
    }

    public function getItemCountPerPage()
    {
        if (empty($this->itemCountPerPage)) {
            $this->itemCountPerPage = self::$defaultItemCountPerPage;
        }

        return $this->itemCountPerPage;
    }

    public function setItemCountPerPage($itemCountPerPage = -1)
    {
        $this->itemCountPerPage = (integer) $itemCountPerPage;
        if ($this->itemCountPerPage < 1) {
            $this->itemCountPerPage = $this->getTotalItemCount();
        }

        $this->getPageCount();

        return $this;
    }

    public function getPageCount()
    {
        if (!$this->pageCount) {
            $this->pageCount = (integer) ceil($this->getTotalItemCount() / $this->getItemCountPerPage());
        }

        return $this->pageCount;
    }

    public function getPages()
    {
        $pageCount         = $this->getPageCount();
        $currentPageNumber = $this->getCurrentPageNumber();

        $pages = new stdClass();
        $pages->pageCount        = $pageCount;
        $pages->itemCountPerPage = $this->getItemCountPerPage();
        $pages->first            = 1;
        $pages->current          = $currentPageNumber;
        $pages->last             = $pageCount;

        // Previous and next
        if ($currentPageNumber - 1 > 0) {
            $pages->previous = $currentPageNumber - 1;
        }else{
            $pages->previous = 0;
        }

        if ($currentPageNumber + 1 <= $pageCount) {
            $pages->next = $currentPageNumber + 1;
        }else{
            $pages->next = 0;
        }

        // Pages in range
        $pages->pagesInRange     = $this->getScrollingStylePages();
        $pages->firstPageInRange = min($pages->pagesInRange);
        $pages->lastPageInRange  = max($pages->pagesInRange);

        return $pages;
    }

    public function getScrollingStylePages()
    {
        $pageCount  = $this->getPageCount();
        $pageRange  = $this->getPageRange();
        $pageNumber = $this->getCurrentPageNumber();

        $originalPageRange = $pageRange;
        $pageRange         = $pageRange * 2 - 1;

        if ($originalPageRange + $pageNumber - 1 < $pageRange) {
            $pageRange = $originalPageRange + $pageNumber - 1;
        } elseif ($originalPageRange + $pageNumber - 1 > $pageCount) {
            $pageRange = $originalPageRange + $pageCount - $pageNumber;
        }

        $pageRange = $this->getPageRange();

        $pageNumber = $this->getCurrentPageNumber();
        $pageCount  = $this->getPageCount();

        if ($pageRange > $pageCount) {
            $pageRange = $pageCount;
        }

        $delta = ceil($pageRange / 2);

        if ($pageNumber - $delta > $pageCount - $pageRange) {
            $lowerBound = $pageCount - $pageRange + 1;
            $upperBound = $pageCount;
        } else {
            if ($pageNumber - $delta < 0) {
                $delta = $pageNumber;
            }

            $offset     = $pageNumber - $delta;
            $lowerBound = $offset + 1;
            $upperBound = $offset + $pageRange;
        }

        return $this->getPagesInRange($lowerBound, $upperBound);
    }

    public function getPagesInRange($lowerBound, $upperBound)
    {
        $lowerBound = $this->normalizePageNumber($lowerBound);
        $upperBound = $this->normalizePageNumber($upperBound);

        $pages = array();

        for ($pageNumber = $lowerBound; $pageNumber <= $upperBound; $pageNumber++) {
            $pages[$pageNumber] = $pageNumber;
        }

        return $pages;
    }

    public function normalizePageNumber($pageNumber)
    {
        $pageNumber = (integer) $pageNumber;

        if ($pageNumber < 1) {
            $pageNumber = 1;
        }

        $pageCount = $this->getPageCount();

        if ($pageCount > 0 && $pageNumber > $pageCount) {
            $pageNumber = $pageCount;
        }

        return $pageNumber;
    }

    public function getCurrentPageNumber()
    {
        return $this->normalizePageNumber($this->currentPageNumber);
    }

    public function setCurrentPageNumber($pageNumber)
    {
        $this->currentPageNumber = (integer) $pageNumber;

        return $this;
    }
}

?>