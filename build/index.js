const { registerBlockType } = wp.blocks;
const { RichText } = wp.blockEditor;

registerBlockType('wprg/rabbit-game-block', {
    title: 'Rabbit Game Block',
    icon: 'admin-customizer',
    category: 'widgets',
    attributes: {
        content: {
            type: 'string',
            source: 'html',
            selector: 'p',
        },
    },
    edit: (props) => {
        return (
            wp.element.createElement(RichText, {
                tagName: 'p',
                className: props.className,
                value: props.attributes.content,
                onChange: (content) => props.setAttributes({ content }),
                placeholder: 'Write here...',
            })
        );
    },
    save: (props) => {
        return wp.element.createElement(RichText.Content, {
            tagName: 'p',
            value: props.attributes.content,
        });
    },
});

registerBlockType('wprg/upcoming-games', {
    title: 'Upcoming Games (RAWG)',
    icon: 'schedule',
    category: 'widgets',
    supports: {
        html: false,
    },
    edit: () => {
        return (
            wp.element.createElement('p', {}, 'Upcoming Games block (rendered on frontend).')
        );
    },
    save: () => {
        // Important: Must return null for server-side render
        return null;
    },
});

