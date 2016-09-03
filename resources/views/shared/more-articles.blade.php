@define $randomArticles = App\Models\Article::whereNotNull('published_at')->where('published_at', '<', new DateTime())->get()->random(2)

<h3>Read more</h3>
<ul class="article-list">
    @foreach($randomArticles as $article)
        <li>{{ link_to('article/'.$article->slug, $article->title) }}</li>
    @endforeach
</ul>