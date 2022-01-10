<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Popup;
use App\Models\Setup;
use App\Models\Slider;
use App\Models\Article;
use App\Models\Contact;
use App\Models\Vacancy;
use App\Models\Category;
use App\Models\Location;
use App\Models\Application;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function Category()
    {
        $categories = Category::orderBy('created_at', 'DESC')->where('show_in_home', 1)->with('parent', 'custom_fields', 'products', 'packs', 'childrenRecursive')->get();
        return response()->json($categories);
    }

    public function CategoryPost(Request $request,$slug)
    {

        $category = Category::where('slug', $slug)->where('status', 1)->with('childrens')->first();
        if (!$category) {
            abort(404);
        }
        $childrens = $category->childrens->pluck('id')->toArray();
        $childrens[] = $category->id;
        $posts = Post::where('status', 1)->notSold()->notExpire()->with('Location')->get();
        $posts = $posts->whereIn('category_id', $childrens);

        if ($request->condition) {
            $posts->whereIn('condition', $request->condition);
        }

        //Price Filtering
        if ($request->price) {
            $max = 0;
            foreach (array($request->price) as $price) {
                if ($max < $price) {
                    $max = $price;
                }
            }
            $posts = $posts->where('selling_price', '<=', $max);
        }

        // Custom field filtering
        $postids = [];
        $fields = [];
        $categories = Category::with('custom_fields')->find($childrens);
        foreach ($categories as $cat) {
            foreach ($cat->custom_fields as $field) {
                if (!in_array($field, $fields)) {
                    $fields[] = $field;
                }
            }
        }

        foreach ($fields as $field) {
            if ($field->highlight) {
                if ($field->type == "OPTIONS") {
                    $field_slug = \Illuminate\Support\Str::slug($field->title);
                    if (isset($request->$field_slug)) {
                        foreach ($request->$field_slug as $custom) {
                            foreach ($posts->get() as $post) {
                                foreach (json_decode($post->custom_fields) as $key => $val) {
                                    if ($key == $field->id && $val == $custom) {
                                        $postids[] = $post->id;
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }

        if (!empty($postids)) {

            $posts->whereIn('id', $postids);
        }

        //Location filter
        if ($request->location) {
            $posts->whereIn('location_id', $request->location_id);
        }

        //Sort Filter

        if ($request->sort) {
            if ($request->sort == "new") {
                $posts->orderBy('created_at', 'DESC');
            }
            if ($request->sort == "high") {
                $posts->orderBy('selling_price', 'DESC');
            }
            if ($request->sort == "low") {
                $posts->orderBy('selling_price', 'ASC');
            }
            if ($request->sort == "visited") {
                $posts->orderBy('views', 'DESC');
            }
        }

        $posts = $posts->paginate(10);
        $totalpost = Post::whereIn('category_id', $childrens)->where('status', 1)->where('is_sold', 0)->notExpire()->get();

        return response()->json($totalpost,$posts);
    }

    public function Location()
    {
        $locations = Location::orderBy('created_at', 'DESC')->where('show_in_home', 1)->with('location', 'posts')->get();
        return response()->json($locations);
    }

    public function FeaturedPost(Request $request)
    {
        $posts = Post::where('status', 1)->notSold()->notExpire()->where('featured', 1)->orderBy('updated_at', 'DESC')->with('Location');

        //Price Filtering
        if ($request->price) {
            $max = 0;
            foreach (array($request->price) as $price) {
                if ($max < $price) {
                    $max = $price;
                }
            }
            $posts = $posts->where('selling_price', '<=', $max);
        }
        if ($request->condition) {
            $posts->whereIn('condition', $request->condition);
        }
        $childrens = $posts->pluck('category_id')->toArray();

        // Custom field filtering
        $postids = [];
        $fields = [];
        $categories = Category::with('custom_fields')->find($childrens);
        foreach ($categories as $cat) {
            foreach ($cat->custom_fields as $field) {
                if (!in_array($field, $fields)) {
                    $fields[] = $field;
                }
            }
        }

        foreach ($fields as $field) {
            if ($field->highlight) {
                if ($field->type == "OPTIONS") {
                    $field_slug = \Illuminate\Support\Str::slug($field->title);
                    if (isset($request->$field_slug)) {
                        foreach ($request->$field_slug as $custom) {
                            foreach ($posts->get() as $post) {
                                foreach (json_decode($post->custom_fields) as $key => $val) {
                                    if ($key == $field->id && $val == $custom) {
                                        $postids[] = $post->id;
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }

        if (!empty($postids)) {

            $posts->whereIn('id', $postids);
        }

        //Location filter
        if ($request->location) {
            $posts->whereIn('location', $request->location);
        }

        //Sort Filter

        if ($request->sort) {
            if ($request->sort == "new") {
                $posts->orderBy('created_at', 'DESC');
            }
            if ($request->sort == "high") {
                $posts->orderBy('selling_price', 'DESC');
            }
            if ($request->sort == "low") {
                $posts->orderBy('selling_price', 'ASC');
            }
            if ($request->sort == "visited") {
                $posts->orderBy('views', 'DESC');
            }
        }

        $posts = $posts->paginate(10);
        $totalpost = Post::where('status', 1)->notSold()->notExpire()->where('featured', 1)->orderBy('updated_at', 'DESC')->get();

        return response()->json([$totalpost,$posts]);
    }

    public function TrendingPost(Request $request)
    {
        $posts = Post::where('status', 1)->notSold()->notExpire()->orderBy('views', 'DESC')->with('Location');

        //Price Filtering
        if ($request->price) {
            $max = 0;
            foreach (array($request->price) as $price) {
                if ($max < $price) {
                    $max = $price;
                }
            }
            $posts = $posts->where('selling_price', '<=', $max);
        }
        if ($request->condition) {
            $posts->whereIn('condition', $request->condition);
        }
        $childrens = $posts->pluck('category_id')->toArray();

        // Custom field filtering
        $postids = [];
        $fields = [];
        $categories = Category::with('custom_fields')->find($childrens);
        foreach ($categories as $cat) {
            foreach ($cat->custom_fields as $field) {
                if (!in_array($field, $fields)) {
                    $fields[] = $field;
                }
            }
        }

        foreach ($fields as $field) {
            if ($field->highlight) {
                if ($field->type == "OPTIONS") {
                    $field_slug = \Illuminate\Support\Str::slug($field->title);
                    if (isset($request->$field_slug)) {
                        foreach ($request->$field_slug as $custom) {
                            foreach ($posts->get() as $post) {
                                foreach (json_decode($post->custom_fields) as $key => $val) {
                                    if ($key == $field->id && $val == $custom) {
                                        $postids[] = $post->id;
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }

        if (!empty($postids)) {

            $posts->whereIn('id', $postids);
        }

        //Location filter
        if ($request->location) {
            $posts->whereIn('location_id', $request->location_id);
        }

        //Sort Filter

        if ($request->sort) {
            if ($request->sort == "new") {
                $posts->orderBy('created_at', 'DESC');
            }
            if ($request->sort == "high") {
                $posts->orderBy('selling_price', 'DESC');
            }
            if ($request->sort == "low") {
                $posts->orderBy('selling_price', 'ASC');
            }
            if ($request->sort == "visited") {
                $posts->orderBy('views', 'DESC');
            }
        }

        $posts = $posts->paginate(10);
        $totalpost = Post::where('status', 1)->notSold()->notExpire()->orderBy('views', 'DESC')->get();

        return response()->json([$totalpost,$posts]);
    }

    public function RecentPost(Request $request)
    {
        $posts = Post::where('status', 1)->notSold()->notExpire()->orderBy('updated_at', 'DESC')->with('Location');

        //Price Filtering
        if ($request->price) {
            $max = 0;
            foreach (array($request->price) as $price) {
                if ($max < $price) {
                    $max = $price;
                }
            }
            $posts = $posts->where('selling_price', '<=', $max);
        }
        if ($request->condition) {
            $posts->whereIn('condition', $request->condition);
        }
        $childrens = $posts->pluck('category_id')->toArray();

        // Custom field filtering
        $postids = [];
        $fields = [];
        $categories = Category::with('custom_fields')->find($childrens);
        foreach ($categories as $cat) {
            foreach ($cat->custom_fields as $field) {
                if (!in_array($field, $fields)) {
                    $fields[] = $field;
                }
            }
        }

        foreach ($fields as $field) {
            if ($field->highlight) {
                if ($field->type == "OPTIONS") {
                    $field_slug = \Illuminate\Support\Str::slug($field->title);
                    if (isset($request->$field_slug)) {
                        foreach ($request->$field_slug as $custom) {
                            foreach ($posts->get() as $post) {
                                foreach (json_decode($post->custom_fields) as $key => $val) {
                                    if ($key == $field->id && $val == $custom) {
                                        $postids[] = $post->id;
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }

        if (!empty($postids)) {

            $posts->whereIn('id', $postids);
        }

        //Location filter
        if ($request->location) {
            $posts->whereIn('location_id', $request->location_id);
        }

        //Sort Filter

        if ($request->sort) {
            if ($request->sort == "new") {
                $posts->orderBy('created_at', 'DESC');
            }
            if ($request->sort == "high") {
                $posts->orderBy('selling_price', 'DESC');
            }
            if ($request->sort == "low") {
                $posts->orderBy('selling_price', 'ASC');
            }
            if ($request->sort == "visited") {
                $posts->orderBy('views', 'DESC');
            }
        }

        $posts = $posts->paginate(10);
        $totalpost = Post::where('status', 1)->notSold()->notExpire()->orderBy('updated_at', 'DESC')->get();

        return response()->json([$totalpost,$posts]);
    }

    public function PostDetail($slug)
    {
        $post = Post::where('slug', $slug)->where('status', 1)->notExpire()->notSold()->with('user', 'category')->first();

        return response()->json($post);
    }

    public function Slider()
    {
        $sliders = Slider::where('status', 1)->orderBy('created_at', 'DESC')->get();
        return response()->json($sliders);
    }

    public function Popup()
    {
        $popups = Popup::where('status', 1)->orderBy('created_at', 'DESC')->get();
        return response()->json($popups);
    }

    public function Article()
    {

        $articles = Article::orderBy('created_at', 'DESC')->where('publish', 1)->get();

        return response()->json($articles);

    }

    public function ArticleDetail($slug)
    {

        $article = Article::where('slug', $slug)->where('publish', 1)->first();

        if (!$article) {
            abort(404);
        }

        return response()->json($article);

    }

    public function Setup()
    {
        $setup = Setup::find(1);

        return response()->json($setup);
    }

    public function Faq()
    {
        $faqs = Faq::orderBy('position', 'ASC')->where('status', 1)->where('type', 'Faq')->get();
        return response()->json($faqs);
    }
    public function Help()
    {
        $helps = Faq::orderBy('position', 'ASC')->where('status', 1)->where('type', 'Help')->get();
        return response()->json($helps);
    }

    public function Compare(Request $request)
    {
        $compares = [];
        $products = [];
        if ($request->compare) {
            $compares = Post::where('id', $request->compare)->notSold()->notExpire()->where('status', 1)->get();
            $products[] = $request->compare;
        }

        return response()->json($products,$compares);
    }

    public function Search(Request $request)
    {
        if (!$request->key) {
            abort(404);
        }
        $searchkey = $request->key;
        $posts = Post::where('status', 1)->notSold()->notExpire();
        $posts->where('title', 'LIKE', '%' . $searchkey . '%');
        if ($request->categorykey) {
            $category = Category::find($request->categorykey);
            $childids = $category->childrens->pluck('id')->toArray();
            $childids[] = $category->id;
            $posts->whereIn('category_id', $childids);
        }
        $categories = $posts->pluck('category_id')->toArray();

        if ($request->condition) {
            $posts->whereIn('condition', $request->condition);
        }

        //Price Filtering
        if ($request->price) {
            $max = 0;
            foreach (array($request->price) as $price) {
                if ($max < $price) {
                    $max = $price;
                }
            }
            $posts = $posts->where('selling_price', '<=', $max);
        }

        // Custom field filtering
        $postids = [];
        $fields = [];
        $categories = Category::find($categories);
        foreach ($categories as $cat) {
            foreach ($cat->custom_fields as $field) {
                if (!in_array($field, $fields)) {
                    $fields[] = $field;
                }
            }
        }
        foreach ($fields as $field) {
            if ($field->highlight) {
                if ($field->type == "OPTIONS") {
                    $field_slug = \Illuminate\Support\Str::slug($field->title);
                    if (isset($request->$field_slug)) {
                        foreach ($request->$field_slug as $custom) {
                            foreach ($posts->get() as $post) {
                                foreach (json_decode($post->custom_fields) as $key => $val) {
                                    if ($key == $field->id && $val == $custom) {
                                        $postids[] = $post->id;
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }

        if (!empty($postids)) {

            $posts->whereIn('id', $postids);
        }

        //Location filter
        if ($request->location) {
            $posts->whereIn('location_id', $request->location_id);
        }

        //Sort Filter

        if ($request->sort) {
            if ($request->sort == "new") {
                $posts->orderBy('created_at', 'DESC');
            }
            if ($request->sort == "high") {
                $posts->orderBy('selling_price', 'DESC');
            }
            if ($request->sort == "low") {
                $posts->orderBy('selling_price', 'ASC');
            }
            if ($request->sort == "visited") {
                $posts->orderBy('views', 'DESC');
            }
        }
        $posts = $posts->paginate(10);
        $totalpost = Post::where('title', 'LIKE', '%' . $searchkey . '%')->notSold()->notExpire()->get();

        return response()->json($searchkey,$posts,$totalpost,$categories);

    }

    public function sendApplication(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'vacancy_id' => 'required',
            'resume' => 'required'
        ]);
        $application = new Application();
        $application->name = $request->name;
        $application->email = $request->email;
        $application->phone = $request->phone;
        $application->vacancy_id = $request->vacancy_id;
        $resumeName = Carbon::now()->timestamp . '.' . $request->resume->extension();
        $request->resume->storeAs('public/applications', $resumeName);
        $application->resume = $resumeName;
        $application->save();

        return response()->json($application);
    }

    public function sendContact(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|string',
            'phone' => 'required|min:10',
            'subject' => 'required|string',
            'message' => 'required'
        ]);

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->save();

        return response()->json($contact);
    }

    public function Career()
    {
        $vacancies = Vacancy::where('status', 1)->whereDate('expire_date', '>=', now())->get();

        return response()->json($vacancies);
    }

    public function CareerDetail($slug)
    {

        $vacancy = Vacancy::where('slug', $slug)->where('status', 1)->whereDate('expire_date', '>=', now())->first();

        if (!$vacancy) {
            abort(404);
        }

        return response()->json($vacancy);

    }
}
