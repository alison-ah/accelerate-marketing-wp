
jQuery(document).ready(function ($) {
    /*
    * Functionalities of Words & Character limits of testimonial title and content 
    */
    $('.sp-tpro-fronted-form').each(function () {
        var formID = '#' + $(this).attr('id');
        var titleID = $(formID).find('.sp-tpro-form-title').attr('id');

        // Function to limit the input to a maximum characters.
        function maximumCharacterLimit(selector, displayCounter, limit) {
            $(selector).on('keydown', function () {
                var input = $(this).val();
                // Truncate if exceeds limit
                if (input.length > limit) {
                    input = input.slice(0, limit);
                    $(this).val(input);
                }
                $(displayCounter).text(input.length + " characters out of " + limit);
            });
        }
        // Function to limit the input to a maximum words.
        function maximumWordLimit(selector, displayCounter, limit) {
            $(selector).on('keydown', function (e) {
                var input = $(this).val();
                var words = input.trim().split(/\s+/);
                if (words.length > limit) {
                    var truncated = words.slice(0, limit).join(" ");
                    $(this).val(truncated);
                }
                $(displayCounter).text(words.length + " words out of " + limit);
            });
        }

        titleWordLimit = '';
        titleCharLimit = '';
        titleLimitType = '';
        if ($(formID).find('#sp-maximum_length').length > 0) {
            var spTestimonialLimit = $(formID).find('#sp-maximum_length').data('length_type'),
                titleWordLimit = spTestimonialLimit.words,
                titleCharLimit = spTestimonialLimit.characters,
                titleLimitType = spTestimonialLimit.type;

            // Display the character or word counter dynamic text of testimonial Title.
            if (titleLimitType === 'characters') {
                maximumCharacterLimit('#tpro_testimonial_title' + titleID, '#sp-maximum_length', titleCharLimit);
            } else {
                maximumWordLimit('#tpro_testimonial_title' + titleID, '#sp-maximum_length', titleWordLimit);
            }
        }

        contentWordLimit = '';
        contentCharLimit = '';
        contentLimitType = '';
        if ($(formID).find('#sp-content_maximum_length').length > 0) {
            var spTestimonialContentLimit = $(formID).find('#sp-content_maximum_length').data('length_type'),
                contentWordLimit = spTestimonialContentLimit.words,
                contentCharLimit = spTestimonialContentLimit.characters,
                contentLimitType = spTestimonialContentLimit.type;

            // Display the character or word counter dynamic text of testimonial Content.
            if (contentLimitType === 'characters') {
                maximumCharacterLimit('#tpro_client_testimonial' + titleID, '#sp-content_maximum_length', contentCharLimit);
            } else {
                maximumWordLimit('#tpro_client_testimonial' + titleID, '#sp-content_maximum_length', contentWordLimit);
            }
        }

        // Remove message after form submit.
       if($('.sp-tpro-form-validation-msg').length > 0) {
        setTimeout(() => {
            $(formID).find('.sp-tpro-form-validation-msg').remove();
        }, 6000);
       }

    });
});