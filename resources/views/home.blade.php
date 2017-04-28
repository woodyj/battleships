@extends('layouts.app')

@section('content')
    <div>
        <table>
            <thead>
                <tr>
                    <td></td>
                    @php
                        $alphabet = implode(range('a', 'z'));

                        for ($col=0; $col<=9; $col++) {
                            print "<th>" . @$alphabet[$col] . "</th>";
                        }
                    @endphp
                </tr>
            </thead>
            @php
                for ($row=1; $row<=10; $row++) {
                    print "<tr>";

                    for ($col=0; $col<=10; $col++) {
                        if ($col === 0) {
                            print "<th>" . $row . "</th>";
                            continue;
                        }

                        $gridStatus = $gridStatuses[$col][$row];
                        $highlight = $gridStatus['highlight'] ? 'highlight' : '';
                        print "<td class=\"{$highlight}\">" . $gridStatus['status'] . "</td>";
                    }

                    print "</tr>";
                }
            @endphp
        </table>
    </div>
    <div>{{ $damageReport }}</div>
    <div>
        <form action="/command" method="post">
            {{ csrf_field() }}
            <label>Command&nbsp;<input name="command" autofocus/></label>
            <input type="submit" />
        </form>
    </div>
    <div><a href="{{ @route('reset') }}">Restart game</a></div>
@endsection