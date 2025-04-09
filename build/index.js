const { registerBlockType } = wp.blocks;
const { RichText, InspectorControls } = wp.blockEditor;
const { PanelBody, ToggleControl, TextControl, SelectControl } = wp.components;
const { __ } = wp.i18n;

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
          showTitle: {
            type: 'boolean',
            default: true,
        },
         buttonText: {
            type: 'string',
            default: 'Click Me',
        },
        buttonStyle: {
            type: 'string',
            default: 'primary',
        },
        selectGame: {
            type: 'string',
            default: 'wp_rabbit_genres',
        },
    },
    edit: function (props) {
        const { attributes, setAttributes } = props;
        const { content, showTitle, buttonText, buttonStyle, selectGame } = attributes;

        return [
            wp.element.createElement(
                InspectorControls,
                null,
                wp.element.createElement(
                    PanelBody,
                    { title: __('Block Settings', 'myplugin'), initialOpen: true },
                    wp.element.createElement(ToggleControl, {
                        label: __('Show Title', 'myplugin'),
                        checked: showTitle,
                        onChange: (val) => setAttributes({ showTitle: val }),
                    }),
                    wp.element.createElement(TextControl, {
                        label: __('Button Text', 'myplugin'),
                        value: buttonText,
                        onChange: (val) => setAttributes({ buttonText: val }),
                    }),
                     wp.element.createElement(SelectControl, {
                        label: __('Select Game', 'myplugin'),
                        value: selectGame,
                        options: [
                            { label: 'Genre', value: 'wp_rabbit_genres' },
                            { label: 'Platform', value: 'wp_rabbit_platforms' },
                            { label: 'Extra', value: 'wp_rabbit_extra' },
                        ],
                        onChange: (val) => setAttributes({ selectGame: val }),
                    }),
                    wp.element.createElement(SelectControl, {
                        label: __('Button Style', 'myplugin'),
                        value: buttonStyle,
                        options: [
                            { label: 'Primary', value: 'primary' },
                            { label: 'Secondary', value: 'secondary' },
                            { label: 'Outline', value: 'outline' },
                        ],
                        onChange: (val) => setAttributes({ buttonStyle: val }),
                    })
                )
            ),
            wp.element.createElement('div', { className: props.className },
                showTitle && wp.element.createElement('h3', null, 'Title Goes Here'),
                wp.element.createElement(RichText, {
                    tagName: 'p',
                    value: content,
                    onChange: (val) => setAttributes({ content: val }),
                    placeholder: __('Write something...', 'myplugin'),
                }),
                wp.element.createElement('p', {
                    value: selectGame,
                    onChange: (event) => setAttributes({ selectGame: event.target.value }),
                }),
                wp.element.createElement('button', {
                    className: 'wp-block-button__link',
                }, buttonText)
            )
        ];
    },

    save: function (props) {
        const { attributes } = props;
        const { content, showTitle, buttonText, buttonStyle, selectGame } = attributes;

        return wp.element.createElement('div', { className: `btn btn-${buttonStyle}` },
            showTitle && wp.element.createElement('h3', null, 'Title Goes Here'),
            wp.element.createElement(RichText.Content, {
                tagName: 'p',
                value: content,
            }),
            wp.element.createElement('p', {
                value: selectGame,
            }),
            wp.element.createElement('button', {
                className: 'wp-block-button__link',
            }, buttonText)
        );
    }
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

registerBlockType('wpgr/upcoming-games', {
    title: 'Upcoming Games (RAWG) Block',
    icon: 'schedule',
    category: 'widgets',
    attributes: {
        content: {
            type: 'string',
            source: 'html',
            selector: 'p',
        },
        className: {
            type: 'string',
            default: 'upcoming-games',
        },
    },
    supports: {
        html: false,
    },
    edit: () => {
        return (
            wp.element.createElement('p', {}, 'Upcoming Games block (new block).')
        );
    },
    save: () => {
        // Important: Must return null for server-side render
        return null;
    },
});