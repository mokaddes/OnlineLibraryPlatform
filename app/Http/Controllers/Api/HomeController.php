<?php

namespace App\Http\Controllers\Api;

use App\Models\Faq;
use App\Models\Blog;
use App\Models\Book;
use App\Models\Package;
use App\Models\Product;
use App\Models\Category;
use App\Models\CustomPage;
use App\Models\BlogCategory;
use App\Traits\RepoResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    use RepoResponse;
    public function category()
    {
        $categories = Category::where('status', 1)->get();
        if ($categories && $categories->count() > 0) {
            return $this->apiResponse(1, 200, 'category is successfully found.', '', $categories);
        }
        return $this->apiResponse(0, 422, 'category is not found.', '', []);
    }

    public function faq()
    {
        $faqs = Faq::where('is_active', '1')->orderBy('order_id', 'asc')->get();
        if ($faqs && $faqs->count() > 0) {
            return $this->apiResponse(1, 200, 'faq is successfully found.', '', $faqs);
        }
        return $this->apiResponse(0, 422, 'faq is not found.', '', []);
    }

    public function homePage()
    {
        $homePage = DB::table('home_page')->where('id', 1)->first();
        $highlightBooks = Product::where('status', 1)->where('is_highlight', 1)->get();
        $monthBook = Product::where('status', 1)->where('is_book_of_month', 1)->get();
        $data = [
            'homePage' => $homePage,
            'highlightBooks' => $highlightBooks,
            'monthBook' => $monthBook
        ];
        return $this->apiResponse(1, 200, 'Data is successfully found.', '', $data);
    }

    public function custompage()
    {
        $customPage = CustomPage::whereIn('url_slug', ['privacy-policy', 'terms-and-conditions'])->get();
        if ($customPage && $customPage->count() > 0) {
            return $this->apiResponse(1, 200, 'Data is successfully found.', '', $customPage);
        }
        return $this->apiResponse(0, 422, 'Data is not found.', '', []);
    }
    public function settings()
    {
        $settings = DB::table('settings')->first();
        if ($settings) {
            return $this->apiResponse(1, 200, 'Data is successfully found.', '', $settings);
        }
        return $this->apiResponse(0, 422, 'Data is not found.', '', []);
    }

    public function package()
    {
        $packages = Package::where('status', 1)->get();

        if ($packages && $packages->count() > 0) {
            $Offerings = Config::get('app.Offerings');
            $Library_Content = Config::get('app.Library_Content');
            $Book_Access = Config::get('app.Book_Access');
            $Blog_Access = Config::get('app.Blog_Access');
            $Forum_Access = Config::get('app.Forum_Access');
            $Book_Club_Access = Config::get('app.Book_Club_Access');

            // Map the config values to each package
            $mappedPackages = $packages->map(function ($package) use ($Offerings, $Library_Content, $Book_Access, $Blog_Access, $Forum_Access, $Book_Club_Access) {
                return [
                    'id' => $package->id,
                    'title' => $package->title,
                    'price' => $package->price,
                    'price_ngn' => $package->price_ngn,
                    'duration' => $package->duration,
                    'is_subscribed' => $package->is_subscribed,
                    'paypalPlan' => $package->plan_id2,
                    'flutterPlan' => $package->plan_id,
                    'offerings' => $Offerings[$package->offerings] ?? '',
                    'library' => $Library_Content[$package->library] ?? '',
                    'book' => $Book_Access[$package->book] ?? '',
                    'blog' => $Blog_Access[$package->blog] ?? '',
                    'forum' => $Forum_Access[$package->forum] ?? '',
                    'club' => $Book_Club_Access[$package->club] ?? '',
                ];
            });

            return $this->apiResponse(1, 200, 'Data is successfully found.', '', $mappedPackages);
        }

        return $this->apiResponse(0, 422, 'Data is not found.', '', []);
    }



}
