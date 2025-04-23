<h1>Franchise Team Members</h1>

<a href="{{ route('franchise.team.create') }}">Add Team Member</a>

<ul>
    @foreach ($teamMembers as $member)
        <li>{{ $member->name }} ({{ $member->email }})</li>
    @endforeach
</ul>
