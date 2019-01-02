<p>
    <b>@lang('vacancies.title')</b>
</p>

<table class="table table-responsive">
    <thead>
    <tr>
        <td>@lang('vacancies.col_name')</td>
        <td>@lang('vacancies.col_specialization')</td>
        <td class="text-right">@lang('vacancies.col_published')</td>
    </tr>
    </thead>
    <tbody>
    @foreach($organization->vacancies()->published()->get() as $vacancy)
        <tr>
            <td><a href="{{ route('vacancies.show', $vacancy) }}">{{ $vacancy->name }}</a></td>
            <td>@lang('vacancies.specialization_' . $vacancy->specialization)</td>
            <td class="text-right date-short">{{ $vacancy->published_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>