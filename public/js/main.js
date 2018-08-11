/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 40);
/******/ })
/************************************************************************/
/******/ ({

/***/ 40:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(41);


/***/ }),

/***/ 41:
/***/ (function(module, exports) {

/**
 * Created by jgmuc on 7/17/2017.
 */
//add bookmark
var tokenElement = $('meta[name="csrf-token"]');
var _token = tokenElement.attr('content');
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': tokenElement.attr('content')
    }
});
function addBookMark(job_id) {

    $.ajax({
        url: '/bookmark',
        data: { _token: _token, job_id: job_id }, //$('form').serialize(),
        type: 'POST',
        success: function success(response) {
            var data = JSON.parse(response);
            notice(data.message, data.status);
            setTimeout(function () {
                window.location.reload();
            }, 2000);
        },
        error: function error(_error) {
            notice('Error processing your request', 'error');
        }
    });
}
//remove bookmark
function removeBookMark(id) {
    $.ajax({
        url: '/bookmark',
        data: { _token: this._token, id: id }, //$('form').serialize(),
        type: 'DELETE',
        success: function success(response) {
            var data = JSON.parse(response);
            notice(data.message, data.status);
            setTimeout(function () {
                window.location.reload();
            }, 2000);
        },
        error: function error(_error2) {
            notice('Error processing your request', 'error');
        }
    });
}
//share job
function shareJob(job_id, jobTitle) {
    var modal = $('#shareJobModal');
    modal.modal('show');
    modal.find('input[name=job_id]').val(job_id);
    modal.find('.modal-title span').text(jobTitle);
}
//complain job
function complainJob(job_id, jobTitle) {
    var modal = $('#complainJobModal');
    modal.modal('show');
    modal.find('input[name=job_id]').val(job_id);
    modal.find('.modal-title span').text(jobTitle);
}
//apply job
function applyJob(job_id, jobTitle) {
    var applyModal = $('#applyJobModal');
    applyModal.find('input[name=job_id]').val(job_id);
    applyModal.find('.modal-title span').text(jobTitle);
    applyModal.modal('show');
}

//delete job
function deleteJob(id) {
    if (!confirm('Are you sure?\n\nThis will also delete bookmarks and applications.')) return false;
    $.ajax({
        url: '/jobs/' + id,
        data: { _token: _token, id: id }, //$('form').serialize(),
        type: 'DELETE',
        success: function success(response) {
            var data = JSON.parse(response);
            if (data.status === 'success') {
                notice(data.message, data.status);
                window.location.reload();
            } else {
                notice(data.message, data.status);
            }
        },
        error: function error(_error3) {
            notice('Unable to process request.', 'error');
        }

    });
}
//updateJobStatus
function updateJobStatus(job_id, status) {
    if (!confirm('You are about to mark job as ' + status)) {
        return;
    }
    $.ajax({
        url: '/update-job-status',
        data: { _token: this._token, job_id: job_id, status: status }, //$('form').serialize(),
        type: 'PATCH',
        success: function success(response) {
            var data = JSON.parse(response);
            notice(data.message, data.status);
            setTimeout(function () {
                window.location.reload();
            }, 2000);
        },
        error: function error(_error4) {
            notice('Error processing your request', 'error');
        }
    });
}

//change application status
function changeApplicationStatus(app_id, status) {
    $.ajax({
        url: '/change-application-status',
        data: { _token: _token, app_id: app_id, status: status }, //$('form').serialize(),
        type: 'POST',
        success: function success(response) {
            var data = JSON.parse(response);
            notice(data.message, data.status);
            window.location.reload();
        },
        error: function error(_error5) {
            notice('Error processing your request', 'error');
        }
    });
}
/**
 * delete job skill
 * @param skill_id
 */
function deleteSkill(skill_id) {
    if (!confirm('Are you sure?')) return false;
    $.ajax({
        url: 'skills/delete/' + skill_id.attr('id'),
        data: { _token: _token }, //$('form').serialize(),
        type: 'POST',
        success: function success(response) {
            notice('Deleted!', 'success');
            window.location.reload();
        },
        error: function error(_error6) {
            notice('Error!', 'error');
        }
    });
}
/**
 * edit job skills
 * @param skill
 */
function editSkill(skill) {
    var skill_id = skill.attr('id');
    $.get('/skills/' + skill_id, function (data) {
        var skill = JSON.parse(data);
        var modal = $('#editSkillModal');
        modal.find('form').attr('action', '/skills/' + skill_id);
        modal.find('input[name=name]').val(skill.name);
        modal.find('textarea[name=desc]').val(skill.desc);
        modal.modal('show');
    });
}
/**
 * delete user skill
 * @param skill_id
 */
function deleteMySkill(skill_id) {
    $.ajax({
        url: '/delete-my-skill',
        data: { _token: _token, skill_id: skill_id }, //$('form').serialize(),
        type: 'POST',
        success: function success(response) {
            notice('Deleted!', 'success');
        },
        error: function error(_error7) {
            notice('Error!', 'error');
        }
    });
}
/**
 * display job
 * @param jobId
 */
function showJob(jobId) {
    $.get('/jobs/' + jobId, function (data, status) {
        var job = JSON.parse(data);
        var viewModal = $('#viewJobModal');
        viewModal.find('#title').html(job.name);
        viewModal.find('#desc').html(job.desc);
        if (job.prettyskills === "") viewModal.find('#skills').html('<span class="label label-warning">None specified</span>');else viewModal.find('#skills').html(job.prettyskills);

        viewModal.find('#cats').html(job.cats);
        viewModal.find('#price').text(job.price);
        viewModal.find('#end-date').text(job.end_date);
        viewModal.find('table').addClass('table');

        var d_level = 'info';
        if (job.level === 'advanced') d_level = 'danger';
        viewModal.find('#difficulty').addClass('label label-' + d_level).text(job.level);

        var bkmrkbtn = viewModal.find('.bookmark-modal-btn');
        if (job.bookmark > 0) {
            bkmrkbtn.attr('id', job.id).removeClass('bookmark-job').addClass('bookmark-job-remove').click(function () {
                removeBookMark(job.bookmark);
            });
            bkmrkbtn.find('i').addClass('text-danger');
        } else {
            bkmrkbtn.attr('id', job.id).removeClass('bookmark-job-remove').addClass('bookmark-job').click(function () {
                addBookMark(job.id);
            });
            bkmrkbtn.find('i').removeClass('text-danger');
        }

        viewModal.modal({ backdrop: 'static', keyboard: false, 'show': true });
    });
}
/**
 * post message in work module
 * @param cmt
 */
function deleteComment(cmt) {
    $.ajax({
        url: '/application/delete-message',
        data: { message_id: cmt.attr('id'), _token: _token }, //$('form').serialize(),
        type: 'POST',
        success: function success(response) {
            notice('Message has been deleted', 'success');
            cmt.closest('.comment').remove();
        },
        error: function error(_error8) {
            notice('Error deleting', 'error');
        }
    });
}
/**
 *
 * @param btn
 */
function postApplicationMessage(btn) {
    var app_id = btn.attr('id');
    var message = $('.new-comment-form').val();

    btn.hide().before('<button class="btn btn-info cm-loading"><i class="fa fa-refresh  fa-spinner fa-spin"></i> sending...</button>');
    if (!message) {
        notice('You need to write something!', 'error');
        $('.cm-loading').hide();
        btn.show();
    } else {
        $.ajax({
            type: "POST",
            url: "/application/post-message",
            data: { app_id: app_id, message: message, _token: _token },
            success: function success(data) {
                notice('Message has been sent', 'success');
                var c = JSON.parse(data);
                if (c.user === null) {
                    c.user = 'User';
                }

                $('.new-comment-form').val('').hide('fast', function () {
                    $('.cm-loading').remove();
                    btn.show();
                    $('.editor').summernote('destroy');
                    $('.editor').summernote();

                    $('.comment-end').before('<div class="callout callout-info" style="padding:10px;margin-bottom:5px;border-bottom:solid 1px #ccc;">' + '<p class="small">Posted by ' + c.user + ' on ' + c.date + '</p>' + message + '</div>');
                });
            },
            error: function error() {
                notice('Something went wrong. Refresh page and try again', 'error');
                $('.cm-loading').remove();
                btn.show();
            }
        });
    }
}

function paypalPayout(app_id, job_id) {
    $.ajax({
        url: '/payout/paypal-payout',
        data: { app_id: app_id, job_id: job_id, _token: _token }, //$('form').serialize(),
        type: 'POST',
        success: function success(response) {
            var msg = JSON.parse(response);
            notice(msg.message, msg.status);
        },
        error: function error(_error9) {
            notice('Application error! Unable to complete request', 'error');
        }
    });
}
$('document').ready(function () {
    //delete skill
    $('.delete-my-skill').click(function () {
        var id = $(this).attr('id');
        deleteMySkill(id);
        $(this).closest('span').remove();
    });
    //delete job
    $('.delete-job').click(function () {
        var id = $(this).attr('id');
        deleteJob(id);
    });
    //view job
    // $('.box .job-desc, .box .panel-heading h4').click(function () {
    //     var jobId = $(this).attr('id');
    //     // window.location.href='/jobs/'+jobId;
    //     showJob(jobId);
    // });
    //bookmark job
    $('.bookmark-job').click(function () {
        var job_id = $(this).attr('id');
        addBookMark(job_id);
    });
    //remove bookmark
    $('.bookmark-job-remove').click(function () {
        var id = $(this).attr('id');
        removeBookMark(id);
    });
    //share job
    $('.share-job-btn').click(function () {
        var job_id = $(this).attr('id');
        var jobTitle = $(this).attr('data-title');
        shareJob(job_id, jobTitle);
    });
    //share job form
    $('.share-job-form').on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        form.append('<input type="hidden" name="_token" value="' + _token + '">');
        $.ajax({
            url: '/shareJob',
            data: $(this).serialize(), //$('form').serialize(),
            type: 'POST',
            success: function success(response) {
                var msg = JSON.parse(response);
                notice(msg.message, msg.status);
                form.find('input').val('');
                form.find('textarea').val('');
                window.location.reload();
            },
            error: function error(_error10) {
                notice('Error! Please try again', 'error');
            }
        });
    });
    //complain job
    $('.complain-job-btn').click(function () {
        var job_id = $(this).attr('id');
        var jobTitle = $(this).attr('data-title');
        complainJob(job_id, jobTitle);
    });
    //complain job form
    $('.complain-job-form').on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        form.append('<input type="hidden" name="_token" value="' + _token + '">');
        $.ajax({
            url: '/complainJob',
            data: $(this).serialize(), //$('form').serialize(),
            type: 'POST',
            success: function success(response) {
                var msg = JSON.parse(response);
                notice(msg.message, msg.status);
                form.find('textarea').val('');
                window.location.reload();
            },
            error: function error(_error11) {
                notice('Error! Please try again', 'error');
            }
        });
    });
    //apply
    $('.apply-job-btn').click(function () {
        var job_id = $(this).attr('id');
        var jobTitle = $(this).attr('data-title');
        applyJob(job_id, jobTitle);
    });
    //apply job form
    $('.apply-job-form').on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        form.find('textarea').hide();
        form.find('.modal-footer').html('<div class="text-center" style="padding:20px;"><i class="fa fa-spinner fa-spin fa-2x"></span></div>');
        $.ajax({
            url: '/applyJob',
            data: $(this).serialize(), //$('form').serialize(),
            type: 'POST',
            success: function success(response) {
                var msg = JSON.parse(response);
                notice(msg.message, msg.status);
                setTimeout(function () {
                    window.location.reload();
                }, 2000);
            },
            error: function error(_error12) {
                notice('Error! Please try again', 'error');
            }
        });
    });
    //change job status
    $(".job-app-status-btn").click(function () {
        var jobId = $(this).attr('id');
        $('#myModal').modal({ backdrop: 'static', keyboard: false, 'show': true }).find('.modal-content').html('<div class="text-center" style="padding:20px;"><i class="fa fa-spinner fa-spin fa-2x"></span></div>').load('/job-app-status/' + jobId);
    });
    //change job status
    $('.change-status').find('li').click(function () {
        changeApplicationStatus($(this).attr('id'), $(this).attr('data-status'));
    });
    //edit skill
    $('.edit-skill').click(function () {
        editSkill($(this));
    });
    //delete skill
    $('.delete-skill').click(function () {
        deleteSkill($(this));
    });
    //notes
    $('.note-group-image-url').parent('div').prepend('You can use services like <a target="_blank" href="https://snag.gy">https://snag.gy</a> to upload image then paste url here');
    //add application message
    $('.add-comment-btn').click(function () {
        postApplicationMessage($(this));
    });
    //delete application message
    $('.del-comment').click(function () {
        deleteComment($(this));
    });

    //payout
    $('.paypal-payout').click(function () {
        var app_id = $(this).attr('id');
        var job_id = $(this).attr('data-job');
        paypalPayout(app_id, job_id);
    });
});

/***/ })

/******/ });