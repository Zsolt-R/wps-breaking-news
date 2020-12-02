jQuery(document).ready(function ($) {

    // Localized data
    const {cta_text,link_text,css_id,hook_to_end,post_data} = wps_bn_meta_data;

    // bail out early
    if(!post_data){
        return;
    }

    /**
     * Setup template
     */
    const {title, permalink} = post_data;

    const content = `<span class="wps-bn-block__intro">${cta_text}</span><span class="wps-bn-block__title">${title}</span>`;
    const post_link = `<a href="${permalink}" class="wps-bn-block__link" title="Read more">${link_text}</a>`;
    const template = `<div class="wps-bn-block"><p class="wps-bn-block__inner">${content}${post_link}</p></div>`;

    /**
     * Hook template into position
     * Change component position based on customizer css id option
     * On custom ID hook to start or end
     */
    if(css_id){
        const component =  $(`#${css_id}`);
        if(hook_to_end){
            component.append(template);
        }else{
            component.prepend(template);
        }
    }else{
         $('body').prepend(template);
    }
})