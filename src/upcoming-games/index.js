import { registerBlockType } from '@wordpress/blocks';

registerBlockType('wprg/upcoming-games', {
    edit: () => {
        return (
            <p>Live preview below (from server).</p>
        );
    },
    save: () => {
        return null; // Important for server-side rendering
    }
});
