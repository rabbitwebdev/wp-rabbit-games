const { registerBlockType } = wp.blocks;

const { TextControl } = wp.components;
const { InspectorControls } = wp.blockEditor;

registerBlockType('wpgr/upcoming-games', {
    title: 'Upcoming Games (RAWG) Block',
    icon: 'schedule',
    category: 'widgets',
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
