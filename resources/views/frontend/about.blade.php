@extends('frontend.layouts.app')
@section('title')
{{ $settings->site_name ?? 'Page header' }}
@endsection

@section('meta')
<meta property="og:title" content="{{ $og_title }}" />
<meta property="og:description" content="{{ $og_description }}" />
<meta property="og:image" content="{{ asset($og_image) }}" />
@endsection

@push('style')
@endpush

@section('content')
<!-- ======================= breadcrumb start  ============================ -->
<div class="breadcrumb_sec">
    <div class="container">
        <div class="breadcrumb_nav text-center">
            <h5>FRANCHISE OPPORTUNITY WEB PORTAL</h5>
            <h2>About us</h2>
        </div>
    </div>
</div>
<!-- ======================= breadcrumb end  ============================ -->

<!-- ======================= about us start  ============================ -->
<div class="about_sec mb-5 mt-5">
    <div class="container">
        <div class="row g-3">
            <div class="col-lg-5">
                <div class="about_bx">
                    <img src="assets/images/2.jpg" class="w-100 rounded-lg border" alt="about us">
                </div>
            </div>
            <div class="col-lg-7">
                <div class="about_info">
                    <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of
                        classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a
                        Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure
                        Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the
                        word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from
                        sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and
                        Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very
                        popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit
                        amet..", comes from a line in section 1.10.32.</p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque consequatur natus deleniti.
                        Eius cum voluptas fuga temporibus velit! Eligendi dolores at nostrum. Quisquam laborum, ut
                        nam impedit amet velit tempora.</p>
                </div>
            </div>

            <div class="about_info">
                <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of
                    classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a
                    Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure
                    Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the
                    word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from
                    sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and
                    Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very
                    popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit
                    amet..", comes from a line in section 1.10.32.</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque consequatur natus deleniti.
                    Eius cum voluptas fuga temporibus velit! Eligendi dolores at nostrum. Quisquam laborum, ut
                    nam impedit amet velit tempora.</p>
                <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of
                    classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a
                    Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure
                    Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the
                    word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from
                    sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and
                    Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very
                    popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit
                    amet..", comes from a line in section 1.10.32.</p>
                <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of
                    classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a
                    Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure
                    Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the
                    word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from
                    sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and
                    Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very
                    popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit
                    amet..", comes from a line in section 1.10.32.</p>
            </div>


        </div>
    </div>
</div>
<!-- ======================= about us end  ============================ -->
@endsection
@push('style')
@endpush
