@extends('frontend.layouts.app')
@section('title')
{{ $title ?? 'Blog' }}
@endsection

@push('style')
@endpush

@section('content')

    <!-- ======================= breadcrumb start  ============================ -->
    <div class="breadcrumb-sec pt-4 pb-4">
        <div class="container">
            <div class="breadcrumb-item text-center">
                <h4>Blog Categories ({{ $slug }})</h4>
                <img src="{{asset('assets/frontend/images/breadcrumb_shape.svg')}}" alt="">
            </div>
        </div>
    </div>
    <!-- ======================= breadcrumb end  ============================ -->
    <!-- ======================= blog start  ============================ -->
    <div class="blog-sec pb-5 mb-5">
        <div class="container">
            <div class="row gx-lg-5 gx-0">
                <div class="col-lg-8 mb-5 mb-lg-0">
                    <div class="row">
                        <div class="col-12 mb-4">
                            @foreach ($data['rows']->where('is_top',1) as $key => $row)
                            @if ($row->is_top == 1)
                            <article class="card article-card">
                                <a href="{{route('frontend.blogs.details',['slug'=>$row->slug])}}">
                                    <div class="card-image">
                                        <div class="post-info">
                                            <span class="text-uppercase">04 Jun 2021</span>
                                            <span class="text-uppercase">3 minutes read</span>
                                        </div>
                                        <img src="{{asset($row->image)}}" alt="Post Thumbnail"
                                            class="rounded w-100">
                                    </div>
                                </a>
                                <div class="card-body px-0 pb-1">
                                    <ul class="post-meta mb-2">
                                        <li>
                                            <a href="#">travel</a>
                                            <a href="#">news</a>
                                        </li>
                                    </ul>
                                    <h2>
                                        <a class="post-title" href="{{route('frontend.blogs.details', 'slug')}}">
                                            {{ $row->title }}
                                        </a>
                                    </h2>
                                    <p class="card-text">
                                       {!! $row->descriptions !!}
                                    </p>
                                    <div class="content">
                                        <a class="read-more-btn" href="{{route('frontend.blogs.details', 'slug')}}">
                                            Read Full Article
                                        </a>
                                    </div>
                                </div>
                            </article>
                            @endif
                            @endforeach
                        </div>
                        @php
                        $itemsPerPage = 1;
                        $filteredData = $data['rows']->where('is_top', 0);
                        $currentPage = request()->get('page', 0);
                        $offset = ($currentPage - 1) * $itemsPerPage;
                        $items = $filteredData->slice($offset, $itemsPerPage);
                        $totalItems = $filteredData->count();
                        $lastPage = ceil($totalItems / $itemsPerPage);
                        @endphp
                            @foreach ($items as $key => $row)
                            <div class="col-md-6 mb-4">
                                <article class="card article-card article-card-sm h-100">
                                    <a href="{{route('frontend.blogs.details',['slug'=>$row->slug])}}">
                                        <div class="card-image">
                                            <div class="post-info">
                                                <span class="text-uppercase">03 Jun 2021</span>
                                                <span class="text-uppercase">2 minutes read</span>
                                            </div>
                                            <img src="{{asset($row->image)}}" alt="Post Thumbnail"
                                                class="rounded w-100">
                                        </div>
                                    </a>
                                    <div class="card-body px-0 pb-0">
                                        <ul class="post-meta mb-2">
                                            <li>
                                                <a href="#">travel</a>
                                            </li>
                                        </ul>
                                        <h2>
                                            <a class="post-title" href="{{route('frontend.blogs.details', 'slug')}}">
                                                {{ $row->title }}
                                            </a>
                                        </h2>
                                        <p class="card-text"> {!! $row->descriptions !!}</p>
                                        <div class="content"> <a class="read-more-btn" href="{{route('frontend.blogs.details', ['slug'=>$row->slug])}}">Read Full
                                                Article</a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            @endforeach
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12">
                                    <nav class="mb-md-50">
                                        <ul class="pagination justify-content-center">
                                             @if ($currentPage >= 1)
                                            <li class="page-item">
                                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}" aria-label="Pagination Arrow">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26"
                                                        fill="currentColor" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </li>
                                            @endif
                                            @for ($i = 1; $i <= $lastPage; $i++)
                                            <li class="page-item{{ $i === $currentPage ? ' active' : '' }}">
                                                @if ($i === $currentPage)
                                                    <span>{{ $i }}</span>
                                                @else
                                                    <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" class="page-link">
                                                        {{ $i }}
                                                    </a>
                                                @endif
                                            </li>
                                            @endfor
                                            @if ($currentPage <= $lastPage)
                                            <li class="page-item">
                                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}" aria-label="Pagination Arrow">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26"
                                                        fill="currentColor" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog_sidebar">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="widget mb-5">
                                    <h2 class="section-title mb-3">Search</h2>
                                    <div class="widget-body">
                                        <form action="#" method="post">
                                            <div class="input-group">
                                                <input type="text" name="search" id="search" class="custom_form form-control"
                                                    placeholder="Search..." required>
                                                <button type="submit" class="input-group-text btn_primary">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-6">
                                <div class="widget mb-5">
                                    <h2 class="section-title mb-3">Categories</h2>
                                    <div class="widget-body">

                                        <ul class="widget-list">
                                            @foreach ($blogCategories as $item)
                                            <li><a href="{{route('frontend.blogs.categories',['slug'=>$item->slug])}}">{{ $item->name }} <span>({{ $item->blogs_count }})</span></a>
                                            </li>
                                            @endforeach
                                        </ul>

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-6">
                                <div class="widget">
                                    <h2 class="section-title mb-3">Tags</h2>
                                    <div class="widget-body">
                                        <ul class="widget-tags">
                                            @foreach ($blogTags as $item)
                                            <li><a href="#">{{ $item->name }} </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ======================= blog end  ============================ -->

@endsection

@push('script')
@endpush
