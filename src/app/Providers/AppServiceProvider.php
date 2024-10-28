<?php

namespace App\Providers;

use App\Domain\Repositories\BaseRepositoryInterface;
use App\Domain\Repositories\BoletoRepositoryInterface;
use App\Domain\Repositories\FileRepositoryInterface;
use App\Domain\Repositories\OperacaoRepositoryInterface;
use App\Domain\Repositories\ProcessamentoRepositoryInterface;
use App\Domain\Repositories\StatusRepositoryInterface;
use App\Infrastructure\Repositories\BaseRepository;
use App\Infrastructure\Repositories\BoletoRepository;
use App\Infrastructure\Repositories\OperacaoRepository;
use App\Infrastructure\Repositories\ProcessamentoRepository;
use App\Infrastructure\Repositories\S3FileRepository;
use App\Infrastructure\Repositories\StatusRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(FileRepositoryInterface::class, S3FileRepository::class);
        $this->app->bind(OperacaoRepositoryInterface::class, OperacaoRepository::class);
        $this->app->bind(ProcessamentoRepositoryInterface::class, ProcessamentoRepository::class);
        $this->app->bind(StatusRepositoryInterface::class, StatusRepository::class);
        $this->app->bind(BoletoRepositoryInterface::class, BoletoRepository::class);
        $this->app->bind(BaseRepository::class, BaseRepositoryInterface::class);

    }

    public function boot()
    {
        //
    }
}
