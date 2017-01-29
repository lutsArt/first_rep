<?php

namespace Site\Controller;

class PortfolioController extends SiteController
{
    public function execute()
    {
        parent::template('page/portfolio.php');
    }
}
