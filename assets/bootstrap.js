import { startStimulusApp } from '@symfony/stimulus-bundle';

import { startStimulusAppp } from '@symfony/stimulus-bridge';

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusAppp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));

const appp = startStimulusApp();
