<div class="d-flex flex-wrap">
    @foreach($users as $user)
        <img
            src="{{ $user->profile_photo_url }}"
            alt="avatar"
            class="rounded-circle ms-n2 mb-1"
            style="width: 30px; height: 30px; object-fit: cover"
            title="{{ $user->name }}"
        >
    @endforeach
</div>
