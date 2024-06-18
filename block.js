const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, SelectControl, RangeControl } = wp.components;
const { withSelect } = wp.data;

registerBlockType('top-5-wp-blogs/top-5-wordpress-blogs', {
    title: 'Top 5 WordPress Blogs',
    icon: 'admin-post',
    category: 'widgets',
    attributes: {
        orderBy: {
            type: 'string',
            default: 'desc'
        },
        order: {
            type: 'string',
            default: 'publishDate'
        },
        numberOfPosts: {
            type: 'number',
            default: 5
        }
    },
    edit: withSelect((select, props) => {
        const { attributes: { orderBy, order, numberOfPosts } } = props;
        const query = {
            order: orderBy,
            orderby: order,
            per_page: numberOfPosts
        };
        return {
            posts: select('core').getEntityRecords('postType', 'post', query)
        };
    })(({ posts, className, attributes, setAttributes }) => {
        if (!posts) {
            return 'Loading...';
        }
        if (posts.length === 0) {
            return 'No posts found.';
        }

        return (
            <div className={className}>
                <InspectorControls>
                    <PanelBody title="Settings">
                        <SelectControl
                            label="Order By"
                            value={attributes.orderBy}
                            options={[
                                { label: 'Ascending', value: 'asc' },
                                { label: 'Descending', value: 'desc' },
                            ]}
                            onChange={(orderBy) => setAttributes({ orderBy })}
                        />
                        <SelectControl
                            label="Order"
                            value={attributes.order}
                            options={[
                                { label: 'Name', value: 'title' },
                                { label: 'Publish Date', value: 'date' },
                            ]}
                            onChange={(order) => setAttributes({ order })}
                        />
                        <RangeControl
                            label="Number of Posts"
                            value={attributes.numberOfPosts}
                            onChange={(numberOfPosts) => setAttributes({ numberOfPosts })}
                            min={1}
                            max={10}
                        />
                    </PanelBody>
                </InspectorControls>
                <div className="top-5-wp-blogs-grid">
                    {posts.map(post => (
                        <div className="top-5-wp-blogs-item" key={post.id}>
                            {post.featured_media && (
                                <img src={post.featured_media_src_url} alt={post.title.rendered} />
                            )}
                            <h2>
                                <a href={post.link}>{post.title.rendered}</a>
                            </h2>
                            <p>{post.excerpt.rendered}</p>
                        </div>
                    ))}
                </div>
            </div>
        );
    }),
    save() {
        return null; // Server-side rendering
    }
});
