@extends('admin.layouts.master')
@section('books', 'active')
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
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">{{ $data['title'] ?? 'Page Header' }}</h3>
                            </div>
                            <div>
                                <a href="{{ route('admin.book.index') }}" class="btn btn-sm btn-primary"><i
                                        class="fa fa-angle-left"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover dataTables">
                            <tbody>
                                <tr>
                                    <td style="width:15%;">Title</td>
                                    <td>The name by jhumpa lahiri</td>
                                </tr>
                                <tr>
                                    <td>Sub title</td>
                                    <td>The name by jhumpa lahiri</td>
                                </tr>
                                <tr>
                                    <td>ISBN10</td>
                                    <td>0121547</td>
                                </tr>
                                <tr>
                                    <td>ISBN13</td>
                                    <td>0121547</td>
                                </tr>
                                <tr>
                                    <td>Publisher</td>
                                    <td>Amazon</td>
                                </tr>
                                <tr>
                                    <td>Publishe year</td>
                                    <td>2015/td>
                                </tr>
                                <tr>
                                    <td>Pages</td>
                                    <td>1247</td>
                                </tr>
                                <tr>
                                    <td>Author</td>
                                    <td>John Doe</td>
                                </tr>
                                <tr>
                                    <td>Physical form</td>
                                    <td>Heard Cover</td>
                                </tr>
                                <tr>
                                    <td>Size</td>
                                    <td>Small</td>
                                </tr>
                                <tr>
                                    <td>Binding</td>
                                    <td>PaperBack</td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <td>
                                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Fugiat, minus sunt
                                        veritatis aliquid praesentium culpa quos corrupti eveniet neque unde eius
                                        perspiciatis, ea autem! Labore voluptatibus tempora eius ratione. Adipisci magnam ut
                                        cupiditate enim similique.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
