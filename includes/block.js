const { registerBlockType } = wp.blocks;
const { TextControl } = wp.components;
const { InspectorControls } = wp.editor;
const { __ } = wp.i18n;
const { Fragment } = wp.element;

registerBlockType('wp-rabbit-games/game', {
    title: __('WP Rabbit Game', 'wp-rabbit-games'),
    icon: 'game',
    category: 'common',
    attributes: {
        id: {
            type: 'string',
            default: '',
        },
    },

    /**
     * Edit function: This renders the block in the editor.
     *
     * @param {Object} props - The properties passed to the block.
     * @param {Object} props.attributes - The attributes of the block.
     * @param {Function} props.setAttributes - Function to update the attributes.
     * @returns {WPElement} The element to render.
     */
    edit({ attributes, setAttributes }) {
        return (
            <Fragment>
                <InspectorControls>
                    <TextControl
                        label={__('Game ID', 'wp-rabbit-games')}
                        value={attributes.id}
                        onChange={(id) => setAttributes({ id })}
                        help={__('Enter the RAWG.io game ID to fetch game details.', 'wp-rabbit-games')}
                    />
                </InspectorControls>
                <div className="wp-rabbit-game-editor">
                    <h3>{__('WP Rabbit Game Block', 'wp-rabbit-games')}</h3>
                    <p>{__('Game ID: ', 'wp-rabbit-games')} {attributes.id}</p>
                    {attributes.id && <p>{__('Fetching game details...', 'wp-rabbit-games')}</p>}
                    {!attributes.id && <p>{__('Please provide a game ID.', 'wp-rabbit-games')}</p>}
                </div>
            </Fragment>
        );
    },

    // Save function: The data displayed on the front-end (empty because it’s dynamically rendered via PHP)
    save() {
        return null; // We don’t need to save anything in the post content; it’s rendered by PHP.
    },

    // Render function (this calls the PHP function to display the game on the front-end)
    render_callback: (attributes) => {
        const game_id = attributes.id;
        if (!game_id) {
            return '<p>' + __('Game ID not set.', 'wp-rabbit-games') + '</p>';
        }

        // This fetches game data using the PHP shortcode functionality
        const game_data = wp_rabbit_games_fetch_game_data(game_id);  // This is a placeholder; PHP will handle the actual API call

        if (!game_data) {
            return '<p>' + __('Error fetching game data.', 'wp-rabbit-games') + '</p>';
        }

        return `
            <div class="wp-rabbit-game">
                <h2>${game_data.name}</h2>
                <img src="${game_data.background_image}" alt="${game_data.name}" />
                <p>${game_data.description_raw}</p>
            </div>
        `;
    }
});
