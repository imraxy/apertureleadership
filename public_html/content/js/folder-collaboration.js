(function ($) {
    var cfg = window.folderCollaboration || {};
    var searchTimer = null;

    function post(url, data, done) {
        $.ajax({
            url: url,
            method: 'POST',
            data: $.extend({ _token: cfg.csrf }, data || {}),
            dataType: 'json',
            success: function (resp) {
                if (done) done(resp);
            },
            error: function (xhr) {
                var msg = 'Something went wrong.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                alert(msg);
            }
        });
    }

    function showInviteMessage(text, isError) {
        var $el = $('#collab-invite-message');
        $el.text(text).removeClass('text-success text-danger');
        $el.addClass(isError ? 'text-danger' : 'text-success');
    }

    function selectedSessionId() {
        var val = $('#collab-session-select').val();
        return val ? parseInt(val, 10) : null;
    }

    $('#collab-new-session').on('click', function () {
        post(cfg.createSessionUrl, {}, function (resp) {
            if (resp.success && resp.session) {
                var $sel = $('#collab-session-select');
                $sel.append(
                    $('<option></option>').val(resp.session.id).text(resp.session.name)
                );
                $sel.val(resp.session.id);
                showInviteMessage('New session created. You can invite people to it now.', false);
            }
        });
    });

    $('#collab-user-search').on('input', function () {
        var q = $(this).val().trim();
        clearTimeout(searchTimer);

        if (q.length < 2) {
            $('#collab-search-results').hide().empty();
            return;
        }

        searchTimer = setTimeout(function () {
            var params = { q: q };
            var sid = selectedSessionId();
            if (sid) {
                params.session_id = sid;
            }

            $.get(cfg.searchUrl, params, function (resp) {
                var $box = $('#collab-search-results').empty();
                if (!resp.users || resp.users.length === 0) {
                    $box.append('<div class="list-group-item">No users found</div>');
                } else {
                    resp.users.forEach(function (u) {
                        var label = u.name + ' <' + u.email + '>';
                        $box.append(
                            '<button type="button" class="list-group-item list-group-item-action collab-pick-user" data-id="' + u.id + '">' +
                            $('<div/>').text(u.name).html() + ' &lt;' + $('<div/>').text(u.email).html() + '&gt;' +
                            '</button>'
                        );
                    });
                }
                $box.show();
            });
        }, 300);
    });

    $(document).on('click', '.collab-pick-user', function () {
        var inviteeId = $(this).data('id');
        var payload = { invitee_id: inviteeId };
        var sid = selectedSessionId();

        if (sid) {
            payload.session_id = sid;
        } else {
            payload.create_new = 1;
        }

        $('#collab-search-results').hide();
        $('#collab-user-search').val('');

        post(cfg.inviteUrl, payload, function (resp) {
            if (resp.success) {
                showInviteMessage(resp.message || 'Invite sent.', false);
                setTimeout(function () { window.location.reload(); }, 800);
            } else {
                showInviteMessage(resp.message || 'Failed.', true);
            }
        });
    });

    $(document).on('click', '.collab-decline', function () {
        var id = $(this).data('invite-id');
        post(cfg.declineUrl + '/' + id + '/decline', {}, function () {
            window.location.reload();
        });
    });

    $(document).on('click', '.collab-cancel-invite', function () {
        var id = $(this).data('invite-id');
        post(cfg.cancelUrl + '/' + id + '/cancel', {}, function () {
            window.location.reload();
        });
    });

    $(document).on('click', '.collab-remove-member', function () {
        var userId = $(this).data('user-id');
        var sessionId = $(this).data('session-id');
        if (!confirm('Remove this member from the session?')) {
            return;
        }
        post(cfg.removeUrl + '/' + userId + '/remove', { session_id: sessionId }, function () {
            window.location.reload();
        });
    });
})(jQuery);
