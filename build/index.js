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
