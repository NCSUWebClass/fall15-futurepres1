(function($) {
    // Top level folder that holds all the pages
    var rootPageFolder = './pages';
    // Top level folder for images
    var rootImageFolder = './images';
    // Top level folder for user images
    var rootUserImageFolder = './user-images';
    // Top level folder for css
    var rootStyleFolder = './css';
    // Default fade in/out time, in ms
    var fadeTime = 500;
    // Default refresh time for presenter view, in ms
    var refreshTime = 5000;

    // Make a string have each first letter uppercase
    var firstLetterUpper = function(string) {
        return string.split(' ').map(function(e) {
            e = e.trim();
            if (e.length != 0) {
                e = e.substring(0, 1).toUpperCase() + e.substring(1).toLowerCase();
            }
            return e;
        }).join(' ');
    };

    // Do an ajax call
    var doAjax = function(method, url, data, success, error) {
        $.ajax({
            url: url,
            method: method,
            data: data,
            cache: false,
            success: success,
            error: error
        });
    };

    // Log the change of page
    var logPageChange = function() {
        doAjax('POST', './ajax/logPageChange.php', {
            page: getFilePath()
        }, function() {}, function() {});
    };

    // Set the history panel
    var setHierarchy = function() {
        var sections = getFilePath().split("/").map(function(e) {
            return e.trim();
        });
        var history = $('div#hierarchy');
        var curPath = '';
        var last = history.find('span').not('#spacer').last().attr('id');
        var reachedLast = typeof last !== 'undefined' && last.length == 0;
        history.empty();
        history.html('<span id="">Home</span>');
        for (var i = 0; i < sections.length; i++) {
            if (sections[i].length == 0) {
                continue;
            }
            curPath += '/' + sections[i];
            var newSpan = '<span id="' + curPath + '">' + firstLetterUpper(sections[i].replace(/-/g, ' ')) + '</span>';
            history.append('<span id="spacer">&nbsp;&nbsp;:&nbsp;&nbsp;</span>' + newSpan);
            if (curPath == last) {
                reachedLast = true;
            }
            if (reachedLast) {
                history.find('span').not('#spacer').last().fadeOut(0).fadeIn(fadeTime);
            }
        }
    };

    // Set the css for this page
    var setCSS = function() {
        $('link:not(.no-delete-css)').remove();
        var sections = getFilePath().split("/").map(function(e) {
            return e.trim();
        });
        var curPath = '';
        for (var i = 0; i < sections.length; i++) {
            if (sections[i].length == 0) {
                continue;
            }
            curPath += '/' + sections[i];
            var newCSS = '<link rel="stylesheet" href="' + rootPageFolder + curPath + '/main.css" type="text/css">';
            $('head').append(newCSS);
        }
    };

    // Load a new page into the dashboard
    var loadPage = function(filePath, onComplete) {
        $('div#dashboard').load(filePath, onComplete);
    };

    // Process the hash into the file path
    var getFilePath = function() {
        return window.location.hash.substring(1).toLowerCase().trim();
    };

    // Get the view count for the page we are on
    var getPageViewCount = function() {
        doAjax('POST', './ajax/getPageCount.php', {
            page: getFilePath(),
            children: 0
        }, function(count) {
            if (parseInt(count) >= 0) {
                $('div#page-count').html(count + ' current viewer' + (parseInt(count) != 1 ? 's' : ''));
            } else {
                $('div#page-count').html('Viewer count unknown');
            }
        }, function() {
            $('div#page-count').html('Viewer count unknown');
        });
    };

    // Get the total view count for children of a particular tile
    var getTotalChildrenViewCount = function(tile) {
        var replace = function(val) {
            if ($(tile).has('span.child-count').length > 0) {
                $(tile).find('span.child-count').html(val);
            } else {
                $(tile).append('<span class="child-count">' + val + '</span>');
            }
        };
        doAjax('POST', './ajax/getPageCount.php', {
            page: getFilePath() + '/' + $(tile).attr('id'),
            children: 1
        }, function(count) {
            replace(parseInt(count) >= 0 ? count : '?');
        }, function() {
            replace('?');
        });
    };

    // Get a list of questions by this page
    var getQuestions = function() {
        var questionsElement = $('div#presenter-view div#questions');
        doAjax('GET', './ajax/getQuestions.php', {}, function(response) {
            if (response.length > 0) {
                var questions = response.split('&&&').map(function(e) {
                    return e.split('===');
                });
                if (questions.length == 0) {
                    questionsElement.html('No Questions');
                } else {
                    questionsElement.html('');
                    for (var i = 0; i < questions.length; i++) {
                        if (typeof questions[i][0] === 'undefined' || typeof questions[i][1] === 'undefined') {
                            break;
                        }
                        if (questions[i][1].trim().length == 0) {
                            continue;
                        }
                        var div = '<div class="row">';
                        div += '<div class="col-xs-2"><span id="' + questions[i][0].trim() + '" class="fa fa-close"></span>&nbsp;&nbsp;&nbsp;';
                        div += questions[i][2].trim() + '</div>';
                        div += '<div class="col-xs-10">' + questions[i][1].trim() + '</div>';
                        div += '</div>';
                        questionsElement.append(div);
                    }
                }
            } else {
                questionsElement.html('<div class="row"><div class="col-xs-12">No questions</div></div>');
            }
        }, function() {
            questionsElement.html('<div class="row"><div class="col-xs-12">No questions</div></div>');
        });
    };

    // Get statistics on a page
    var getPageStatistics = function(url, errorText, selector) {
        var element = $(selector);
        doAjax('GET', url, {}, function(response) {
            if (response.length > 0) {
                var data = response.split('&&&').map(function(e) {
                    return e.split('===');
                });
                if (data.length == 0) {
                    element.html(errorText);
                } else {
                    element.html('');
                    for (var i = 0; i < data.length; i++) {
                        if (typeof data[i][0] === 'undefined' || typeof data[i][1] === 'undefined') {
                            break;
                        }
                        var div = '<div class="row">';
                        div += '<div class="col-xs-9" id="' + data[i][2].trim() + '">' + data[i][0].trim() + '</div>';
                        div += '<div class="col-xs-3 text-right">' + (data[i][1].trim().length > 0 ? data[i][1].trim() : 0) + '</div>';
                        div += '</div>';
                        element.append(div);
                    }
                }
            } else {
                element.html('<div class="row"><div class="col-xs-12" id="no-link-presenter">' + errorText + '</div></div>');
            }
        }, function() {
            element.html('<div class="row"><div class="col-xs-12" id="no-link-presenter">' + errorText + '</div></div>');
        });
    };

    // Get the most upvoted pages
    var getMostUpvoted = function() {
        getPageStatistics('./ajax/getMostUpvoted.php', 'No upvotes data', 'div#presenter-view div#favorites');
    };

    // Get the most visited pages
    var getMostVisited = function() {
        getPageStatistics('./ajax/getMostVisited.php', 'No visits data', 'div#presenter-view div#visited');
    };

    // Get live viewing
    var getLiveViewing = function() {
        getPageStatistics('./ajax/getLiveViewing.php', 'No viewing data', 'div#presenter-view div#viewing');
    };

    $(document).ready(function() {
        // Set the hash (#[whatever]) that will specify the page we are on
        var setHashForPath = function(filePath) {
            window.location.hash = filePath.toLowerCase().trim();
        };

        var refreshTimeout = null;

        // Refresh the presenter view
        var refresh = function() {
            if (refreshTimeout !== null) {
                clearTimeout(refreshTimeout);
            }
            getQuestions();
            getMostUpvoted();
            getMostVisited();
            getLiveViewing();
            getPageViewCount();
            $('div#dashboard div.tile:not(.empty)').each(function() {
                getTotalChildrenViewCount(this);
            });
            refreshTimeout = setTimeout(function() {
                refresh();
            }, refreshTime);
        };

        // Load a new dashboard
        var navigate = function(filePath, extraFunc) {
            // Fade out the dashboard
            $('div#dashboard').animate({
                opacity: '0'
            }, fadeTime, function() {
                // Load the dashboard specified by the file path
                loadPage(rootPageFolder + filePath, function(response, status, xhr) {
                    // If success, set the history panel (i.e. hierarchy), fade back in the dashboard, and execute extraFunc
                    if (status != 'error') {
                        setCSS();
                        setHierarchy();
                        logPageChange();
                        refresh();
                        $('div#heart').fadeIn(fadeTime);
                        $('div#dashboard').animate({
                            opacity: 1
                        }, fadeTime);
                        extraFunc();
                    } else {
                        // On fail, load the home page by setting the hash to home, thereby firing window.onHashChange
                        var filePath = getFilePath().split('/');
                        filePath.pop();
                        setHashForPath(filePath.join('/'));
                    }
                });
            });
        };

        // Go to a page from the presenter view
        $('div#presenter-view div#favorites, div#presenter-view div#visited, div#presenter-view div#viewing').on('click', 'div.row > div:first-child', function() {
            setHashForPath($(this).attr('id'));
        });

        // Refresh the presenter view on click of the refresh button. Spin the button once to show something was done
        $('div#presenter-view').on('click', 'span#refresh-presenter', function() {
            refresh();
            var span = $(this);
            $({
                deg: 0
            }).animate({
                deg: 360
            }, {
                duration: 1000,
                step: function(current) {
                    span.css({
                        'transform': 'rotate(' + (current % 360) + 'deg)'
                    });
                }
            });
        });

        // Answer a question on click
        $('div#presenter-view div#questions').on('click', 'span', function() {
            var span = $(this);
            doAjax('POST', './ajax/answerQuestion.php', {
                id: span.attr('id')
            }, function(response) {
                var spanClass = span.attr('class');
                span.attr('class', 'fa fa-check');
                span.fadeOut(fadeTime, function() {
                    span.parent().parent().slideUp(fadeTime, function() {
                        span.parent().parent().remove();
                    });
                });
            }, function() {});
        });

        // Submit a question on click of the check box
        $('span#submit-dialog').on('click', function(e) {
            // Stop this event from bubbling up
            e.preventDefault();
            e.stopPropagation();

            // Make sure there's actually something in the question field
            var question = $('input#question-submit');
            if (question.val().trim().length == 0) {
                return;
            }

            // Submit the question
            doAjax('POST', './ajax/question.php', {
                page: getFilePath(),
                question: question.val()
            }, function() {
                question.val('');
                var span = $('span#submit-dialog');
                var classAttr = span.attr('class');
                span.attr('class', 'fa fa-check');
                span.fadeOut(fadeTime, function() {
                    span.attr('style', '');
                    span.attr('class', classAttr);
                });
            }, function() {})
        });

        // Go to the desired page when the hierarchy breadcrumbs are clicked
        $('div#hierarchy').on('click', 'span:not(#spacer)', function(e) {
            // Stop this event from bubbling up
            e.preventDefault();
            e.stopPropagation();

            // Fade out everything
            $(this).nextAll().fadeOut(fadeTime);

            // Set the new hash, thereby firing window.onHashChange to load the new dashboard
            setHashForPath($(this).attr('id'));
        });

        // Go to the next page on a tile click
        $('div#dashboard').on('click', '.tile:not(.empty)', function(e) {
            // Stop this event from bubbling up
            e.preventDefault();
            e.stopPropagation();

            // Set the new hash, thereby firing window.onHashChange to load the new dashboard, iff the tile has a child
            var ext = $(this).attr('id');
            if (typeof ext !== 'undefined') {
                setHashForPath(getFilePath() + '/' + ext);
            }
        });

        // Favorite a page on a heart click
        $('div#heart').on('click', function(e) {
            // Stop this event from bubbling up
            e.preventDefault();
            e.stopPropagation();

            doAjax('POST', './ajax/upvote.php', {
                page: getFilePath()
            }, function() {
                $('div#heart').fadeOut(fadeTime);
            }, function() {});
        });

        // Load the new dashboard whenever the window hash changes, since technically the DOM won't reload itself
        $(window).on('hashchange', function(e) {
            // Stop this event from bubbling up
            e.preventDefault();
            e.stopPropagation();

            // Using just a / to represent home can be problematic for the backend. Return ensure not double loading content
            if (getFilePath() == '/') {
                setHashForPath('');
                return;
            }

            $('div#hierarchy').find('span[id=\'' + getFilePath() + '\']').nextAll().fadeOut(fadeTime);

            // Load the dashboard
            navigate(getFilePath(), function() {});
        });

        // Load the dashboard specified by the current hash by triggering the event
        $(window).trigger('hashchange');

    });
})(jQuery);
