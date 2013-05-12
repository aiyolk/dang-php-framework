<?php

/*
 * 分页类
 * @author wuqingcheng
 * @date 2013.04.22
 * @email wqc200@gmail.com
 */

class Dang_Paginator_Paginator
{  
    protected static $defaultItemCountPerPage = 10;
    protected $pageRange = 10;
    protected $pageCount = null;
    protected $currentPageNumber = 1;
    
    public function __construct() {
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
        $this->pageCount        = $this->pageCount();
        
        return $this;
    }
    
    public function pageCount()
    {
        if (!$this->pageCount) {
            $this->pageCount = (integer) ceil($this->getTotalItemCount() / $this->getItemCountPerPage());
        }

        return $this->pageCount;
    }
    
    public function getPages()
    {
        $pageCount         = $this->pageCount();
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
        }

        if ($currentPageNumber + 1 <= $pageCount) {
            $pages->next = $currentPageNumber + 1;
        }

        // Pages in range
        $pages->pagesInRange     = $this->getScrollingStylePages();
        $pages->firstPageInRange = min($pages->pagesInRange);
        $pages->lastPageInRange  = max($pages->pagesInRange);

        // Item numbers
        /*
        $pages->currentItemCount = $this->getCurrentItemCount();
        $pages->itemCountPerPage = $this->getItemCountPerPage();
        $pages->totalItemCount   = $this->getTotalItemCount();
        $pages->firstItemNumber  = (($currentPageNumber - 1) * $this->getItemCountPerPage()) + 1;
        $pages->lastItemNumber   = $pages->firstItemNumber + $pages->currentItemCount - 1;
        */
        
        return $pages;
    }
    
    public function getScrollingStylePages()
    {
        $pageRange  = $this->getPageRange();
        $pageNumber = $this->getCurrentPageNumber();

        $originalPageRange = $pageRange;
        $pageRange         = $pageRange * 2 - 1;

        if ($originalPageRange + $pageNumber - 1 < $pageRange) {
            $pageRange = $originalPageRange + $pageNumber - 1;
        } elseif ($originalPageRange + $pageNumber - 1 > count($paginator)) {
            $pageRange = $originalPageRange + count($paginator) - $pageNumber;
        }

        $pageRange = $this->getPageRange();

        $pageNumber = $this->getCurrentPageNumber();
        $pageCount  = $this->pageCount();

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

        $pageCount = $this->pageCount();

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