@extends('admin.layouts.master')
@section('dashboard', 'active')

@section('title') {{ $data['title'] ?? '' }} @endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $data['title'] ?? 'Page Header' }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <div class="info-box-content">
                                <span class="info-box-text">Total Books</span>
                                <span class="info-box-number">00</span>
                            </div>
                            <a href="#" class="stretched-link"></a>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <div class="info-box-content">
                                <span class="info-box-text">Borrowed Books</span>
                                <span class="info-box-number">00</span>
                            </div>
                            <div class="icon">
                                <i class="ion ion-book"></i>
                            </div>
                            <a href="#" class="stretched-link"></a>
                        </div>
                    </div>

                    <div class="clearfix hidden-md-up"></div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <div class="info-box-content">
                                <span class="info-box-text">Book Views</span>
                                <span class="info-box-number">00</span>
                            </div>
                            <div class="icon">
                                <i class="ion ion-book"></i>
                            </div>
                            <a href="#" class="stretched-link"></a>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <div class="info-box-content">
                                <span class="info-box-text">Remaining Subscription (02 Days)</span>
                                <span class="info-box-number">00</span>
                            </div>
                            <div class="icon">
                                <i class="ion ion-envelope"></i>
                            </div>
                            <a href="#" class="stretched-link"></a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">Latest Book</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.book.index') }}" class="btn btn-sm btn-primary">View All</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                                <th>#</th>
                                <th>Book Name</th>
                                <th>Publisher</th>
                                <th>Year</th>
                                <th>ISBN</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>01</td>
                                    <td>The name by jhumpa lahiri</td>
                                    <td>Amazon Publisher</td>
                                    <td>Feb 2, 23</td>
                                    <td>21547854</td>
                                </tr>
                                <tr>
                                    <td>02</td>
                                    <td>The name by jhumpa lahiri</td>
                                    <td>Amazon Publisher</td>
                                    <td>Feb 2, 23</td>
                                    <td>21547854</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">Latest View Book</h3>
                            </div>
                            <div>
                                <a href="#" class="btn btn-sm btn-primary">View All</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                                <th>#</th>
                                <th>Book Name</th>
                                <th>Publisher</th>
                                <th>Year</th>
                                <th>ISBN</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>01</td>
                                    <td>The name by jhumpa lahiri</td>
                                    <td>Amazon Publisher</td>
                                    <td>Feb 2, 23</td>
                                    <td>21547854</td>
                                </tr>
                                <tr>
                                    <td>02</td>
                                    <td>The name by jhumpa lahiri</td>
                                    <td>Amazon Publisher</td>
                                    <td>Feb 2, 23</td>
                                    <td>21547854</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
