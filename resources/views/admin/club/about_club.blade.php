@extends('admin.layouts.master')
@section('club', 'active')

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
                <div class="row pb-5">
                    <div class="col-lg-8">
                        <div class="card club_wrapper">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="">
                                        <h3 class="card-title">Prompt Engineer Club for Bangladseh</h3>
                                    </div>
                                    <div class="">
                                        <a href="{{route('admin.club.community')}}" class="btn btn-success btn-sm">Join Club</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="club_wrap pt-4 pb-4" style="background-image: url('{{ asset('assets/images/banner.jpg') }}')">
                                    <div class="club_list border-0">
                                        <div class="d-sm-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center position-relative">
                                                <div class="mr-2">
                                                    <img src="{{ asset('assets/images/2.jpg') }}" class="rounded"
                                                    width="50" height="50" alt="">
                                                </div>
                                                <div>
                                                    <h4 class="mb-0">
                                                        <a href="javascript:void(0)" class="text-white">Prompt Engineer Club for Bangladseh</a>
                                                    </h4>
                                                    <span>12,444 Members</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="club_content p-3">
                                    <h4>About Club</h4>
                                    <p class="mb-4">
                                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sit et nobis veritatis quaerat reprehenderit? Sapiente obcaecati, voluptatibus necessitatibus corrupti officiis quaerat iure voluptate repellat reiciendis quasi earum doloribus blanditiis veritatis architecto, consequatur dolorum ea dolorem ipsa hic et minima nobis, accusamus dignissimos. Quae sed perferendis reiciendis, minima aliquam doloribus porro exercitationem quo consequuntur, aut, itaque rem at officiis cumque. Perspiciatis accusamus obcaecati in veritatis, error reprehenderit perferendis incidunt dolorum eum iure quaerat quis qui exercitationem odio quod porro eos tempore, quae neque officiis. Harum, dolor corrupti. Dicta iusto possimus exercitationem quis praesentium dolorum, distinctio, alias ullam laborum sint veritatis totam. Qui nesciunt distinctio incidunt sapiente accusamus, rerum deserunt id corporis possimus non consectetur? Accusamus tempora fugiat enim assumenda repellat velit dolore eum ipsa necessitatibus quae illo modi soluta consectetur, laborum unde at optio.
                                    </p>


                                    <h4>Rules</h4>
                                    <p>
                                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sit et nobis veritatis quaerat reprehenderit? Sapiente obcaecati, voluptatibus necessitatibus corrupti officiis quaerat iure voluptate repellat reiciendis quasi earum doloribus blanditiis veritatis architecto, consequatur dolorum ea dolorem ipsa hic et minima nobis, accusamus dignissimos. Quae sed perferendis reiciendis, minima aliquam doloribus porro exercitationem quo consequuntur, aut, itaque rem at officiis cumque. Perspiciatis accusamus obcaecati in veritatis, error reprehenderit perferendis incidunt dolorum eum iure quaerat quis qui exercitationem odio quod porro eos tempore, quae neque officiis. Harum, dolor corrupti. Dicta iusto possimus exercitationem quis praesentium dolorum, distinctio, alias ullam laborum sint veritatis totam. Qui nesciunt distinctio incidunt sapiente accusamus, rerum deserunt id corporis possimus non consectetur? Accusamus tempora fugiat enim assumenda repellat velit dolore eum ipsa necessitatibus quae illo modi soluta consectetur, laborum unde at optio.
                                    </p>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="col-lg-4">
                        <div class="club_sidebar">


                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">12,5487 Club Members</h3>
                                </div>
                                <div class="card-body all_members">
                                    @for ($i = 0; $i < 8; $i++)
                                        <div class="recent_activity position-relative">
                                            <div class="d-flex">
                                                <div class="mr-2">
                                                    <img src="{{ asset('assets/images/user.jpg') }}" class="rounded"
                                                        width="40" height="40" alt="">
                                                </div>
                                                <div>
                                                    <h4 class="mb-1">
                                                        <a href="#" class="stretched-link">John Doe</a>
                                                    </h4>
                                                    <span>Joined: 1 Feb, 2023</span>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
