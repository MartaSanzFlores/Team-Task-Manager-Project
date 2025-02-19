/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

import './styles/components/list.css';
import './styles/components/alerts.css';
import './styles/components/buttons.css';
import './styles/components/scroll.css';
import './styles/components/fullcalendar.css';

/* JS */
import './js/fullcalendar.js';
import './js/taskList.js';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
