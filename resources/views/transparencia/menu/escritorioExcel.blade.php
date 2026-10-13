<table>
    <thead>
    <tr>
        <th style="background:#211645; color:#ffffff; border:1px solid #000000; font-weight:bold; text-align: right;">Nº Reportes</th>
        <th style="background:#211645; color:#ffffff; border:1px solid #000000; font-weight:bold; text-align: right;">RUN</th>
        <th style="background:#211645; color:#ffffff; border:1px solid #000000; font-weight:bold; text-align: right;">Nombre</th>
        <th style="background:#211645; color:#ffffff; border:1px solid #000000; font-weight:bold; text-align: right;">Cargo</th>
        <th style="background:#211645; color:#ffffff; border:1px solid #000000; font-weight:bold; text-align: right;">Dependencia</th>
        <th style="background:#211645; color:#ffffff; border:1px solid #000000; font-weight:bold; text-align: right;">Perfil</th>
        <th style="background:#211645; color:#ffffff; border:1px solid #000000; font-weight:bold; text-align: right;">Último acceso</th>
    </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                <td style="background:#d1d1f0; color:#211645; border:1px solid #000000; text-align: right;">{{$item->reportes}}</td>
                <td style="background:#d1d1f0; color:#211645; border:1px solid #000000; text-align: right;">{{$item->run_consultas}}</td>
                <td style="background:#d1d1f0; color:#211645; border:1px solid #000000; text-align: right;">{{$item->nombre}}</td>
                <td style="background:#d1d1f0; color:#211645; border:1px solid #000000;">
                    @if(isset($cargos[$item->cargo]->nombre_cargo))
                        {{$cargos[$item->cargo]->nombre_cargo}}
                    @endif
                </td>
                <td style="background:#d1d1f0; color:#211645; border:1px solid #000000;">
                    @if(isset($dependencias[$item->dependencia]->nombre_dependencia))
                        {{$dependencias[$item->dependencia]->nombre_dependencia}}
                    @endif
                </td>
                <td style="background:#d1d1f0; color:#211645; border:1px solid #000000;">
                    @if(isset($perfiles[$item->perfil]->nombre_perfil))
                        {{$perfiles[$item->perfil]->nombre_perfil}}
                    @endif
                </td>
                <td style="background:#d1d1f0; color:#211645; border:1px solid #000000;">
                    @php
                        $fecha = explode(' ', $item->ultimo_acceso);
                        echo ($fecha[0]);
                    @endphp
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
