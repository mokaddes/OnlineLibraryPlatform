@extends('frontend.layouts.app')
@section('title')
{{ $title ?? 'Blog' }}
@endsection

@push('style')
<style>
   .no-data {
    margin-top: 100px;
    font-family: 'Poppins';
    FONT-WEIGHT: 600;
    color: #800080;
    }
    .tag{
        background: #800080 !important;
        color:  #fff !important;
    }
    .category{
        color: #800080 !important;
        font-size: 16px !important;
    }
</style>
@endpush

@section('content')
    <!-- ======================= breadcrumb start  ============================ -->
    <div class="breadcrumb-sec pt-4 pb-4">
        <div class="container">
            <div class="breadcrumb-item text-center">
                <h4>Blog</h4>
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
                            @forelse ($rows->where('is_top',1) as $key => $row)
                            @if ($row->is_top == 1)
                            <article class="card article-card">
                                <a href="{{route('frontend.blogs.details',['slug'=>$row->slug])}}">
                                    <div class="card-image">
                                        <div class="post-info">
                                            <span class="text-uppercase"> {{ $row->created_at->format('d M Y') }}</span>
                                        </div>
                                        <img src="{{asset($row->image)}}" alt="Post Thumbnail"
                                            class="rounded w-100">
                                    </div>
                                </a>
                                <div class="card-body px-0 pb-1">
                                    @if($row->getCategoryMap)
                                    <ul class="post-meta mb-2">
                                        <li>
                                            @foreach($row->getCategoryMap as $key => $val)
                                            <a href="{{route('frontend.blogs', ['category'=>$val->getCategory->slug])}}">{{ $val->getCategory->name ?? '' }}</a>
                                            @endforeach
                                        </li>
                                    </ul>
                                    @endif
                                    <h2>
                                        <a class="post-title" href="{{route('frontend.blogs.details',['slug'=>$row->slug])}}">
                                            {{ $row->title }}
                                        </a>
                                    </h2>
                                    <p class="card-text">
                                       {!! $row->short_descriptions !!}
                                    </p>
                                    <div class="content">
                                        <a class="read-more-btn" href="{{route('frontend.blogs.details', ['slug'=>$row->slug])}}">
                                            Read Full Article
                                        </a>
                                    </div>
                                </div>
                            </article>
                            @endif
                            @empty
                            <div class="text-center">
                                <img src="{{ asset('assets/frontend/images/no-data.gif')}}" alt="no-data">
                               {{-- <p class="no-data">Sorry, we couldn't find any data.</p>  --}}
                            </div> 
                            @endforelse
                        </div>
                            @foreach ($rows->where('is_top',0) as $key => $row)
                            <div class="col-md-6 mb-4">
                                <article class="card article-card article-card-sm h-100">
                                    <a href="{{route('frontend.blogs.details',['slug'=>$row->slug])}}">
                                        <div class="card-image">
                                            <div class="post-info">
                                                <span class="text-uppercase">{{ $row->created_at->format('d M Y') }}</span>
                                            </div>
                                            <img src="{{asset($row->image)}}" alt="Post Thumbnail"
                                                class="rounded w-100">
                                        </div>
                                    </a>
                                    <div class="card-body px-0 pb-0">
                                        @if($row->getCategoryMap)
                                        <ul class="post-meta mb-2">
                                            <li>
                                                @foreach($row->getCategoryMap as $key => $val)
                                                <a href="{{route('frontend.blogs',['category'=>$val->getCategory->slug])}}">{{ $val->getCategory->name ?? '' }}</a>
                                                @endforeach
                                            </li>
                                        </ul>
                                        @endif
                                        <h2>
                                            <a class="post-title" href="{{route('frontend.blogs.details', ['slug'=>$row->slug])}}">
                                                {{ $row->title }}
                                            </a>
                                        </h2>
                                        <p class="card-text"> {!! $row->short_descriptions !!}</p>
                                        <div class="content"> <a class="read-more-btn" href="{{route('frontend.blogs.details', ['slug'=>$row->slug])}}">Read Full
                                                Article</a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            @endforeach
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            {{ $rows->withQueryString()->links() }}
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
                                        <form action="{{ route('frontend.blogs') }}" method="GET">
                                            <div class="input-group">
                                                <input type="text" name="search" id="search" class="custom_form form-control"
                                                    placeholder="Search..." required value="{{ request()->get('search')  ?? '' }}">
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
                                            <li><a @if($category == $item->slug) class="category" @endif  href="{{route('frontend.blogs',['category'=>$item->slug])}}">{{ $item->name }} <span>({{ $item->blogs_count }})</span></a>
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
                                            <li><a @if($tag == $item->slug) class="tag" @endif href="{{ route('frontend.blogs') }}?tag={{ $item->slug }}">{{ $item->name }} </a>
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
