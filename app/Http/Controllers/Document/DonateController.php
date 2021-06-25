<?php

declare(strict_types=1);

namespace App\Http\Controllers\Document;

use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DonateController.
 */
class DonateController extends DocumentController
{
    /**
     * Show the donate document for the application.
     *
     * @return View
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function show(): View
    {
        return $this->displayMarkdownDocument('donate');
    }
}