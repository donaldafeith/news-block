const { registerBlockType } = wp.blocks;
const { useState, useEffect } = wp.element;
const { Spinner } = wp.components;

registerBlockType('news-tech/block', {
    title: 'Tech News',
    icon: 'admin-site-alt3',
    category: 'widgets',
    edit: () => {
        const [articles, setArticles] = useState([]);
        const [loading, setLoading] = useState(true);

        useEffect(() => {
            fetch('https://newsapi.org/v2/everything?q=tech&apiKey=fd39df269143449ab27667795a83d964')
                .then(response => response.json())
                .then(data => {
                    setArticles(data.articles);
                    setLoading(false);
                });
        }, []);

        if (loading) {
            return wp.element.createElement(Spinner, null);
        }

        return wp.element.createElement(
            'div',
            { className: 'news-tech-block' },
            articles.map(article =>
                wp.element.createElement(
                    'div',
                    { key: article.url, className: 'news-article' },
                    [
                        wp.element.createElement(
                            'h3',
                            null,
                            wp.element.createElement(
                                'a',
                                { href: article.url, target: '_blank', rel: 'noopener noreferrer' },
                                article.title
                            )
                        ),
                        wp.element.createElement('p', null, article.description)
                    ]
                )
            )
        );
    },
    save: () => {
        return null; // Dynamic block, content is rendered in PHP
    }
});