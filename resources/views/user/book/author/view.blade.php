@extends('admin.layouts.user')
@section('my_books', 'active')
@section('books', 'active menu-open')
@section('title') {{ $title ?? '' }} @endsection
@push('style')
<style> 
</style>
@endpush
@section('content')
    <div class="content-wrapper mt-3" >
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header" >
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title">Book Details</h3>
                            </div>
                            <div>
                                <a href="{{ route('author.books.index') }}" class="btn btn-sm" id="custom_btn">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table  dataTables">
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
                                    <td>Category</td>
                                    <td>Fasion</td>
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
                                    <td>Size</td>
                                    <td>Small</td>
                                </tr>
                                <tr>
                                    <td>Edition</td>
                                    <td>12th</td>
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
