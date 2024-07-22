async function obtenerIDs() {
    try {
      const result = await $.ajax({
        type: "GET",
        url: `${window.location.origin}/admin/iniciativas/invi/ids`,
      });

      for (const value of result) {
        await calcularIndiceGlobal(value.inic_codigo);
      }
    } catch (error) {
      console.error("Error al obtener los IDs:", error);
    }
  }

  function calcularIndice(inic_codigo) {
    let datos;
    let mecanismo, frecuencia, cobertura, resultados, evaluacion;
    let mecanismo_txt,
      frecuencia_txt,
      cobertura_txt,
      resultados_txt,
      evaluacion_txt;
    let mecanismo_puntaje,
      frecuencia_puntaje,
      cobertura_puntaje,
      resultados_puntaje,
      evaluacion_puntaje;
    let resultado1_aux, resultado2_aux;
    let divisor, dividendo;
    let partInicial, partFinal;
    let resuInicial, resuFinal;
    let indice;

    $.ajax({
      type: "GET",
      url: window.location.origin + "/admin/iniciativa/invi/datos",
      data: {
        iniciativa: inic_codigo,
      },
      success: function (resConsultar) {
        respuesta = JSON.parse(resConsultar);
        datos = respuesta.resultado;

        mecanismo = datos.mecanismo;
        frecuencia = datos.frecuencia;

        cobertura = datos.cobertura;
        coberturaExt = datos.cobertura2;
        resultados2 = datos.resultados2;
        evaluacion = datos.evaluacion;
        resultados_puntaje = 0;

        /* Mecanismo */
        if (mecanismo == null) {
            mecanismo_puntaje = 0;
        }
        if (mecanismo[0].meca_nombre == "Mentoría, Asesoría , Asistencia Técnica") {
            mecanismo_puntaje = 86;
        }else if (mecanismo[0].meca_nombre == "Extensión") {
            mecanismo_puntaje = 60;
        }else if (mecanismo[0].meca_nombre == "Formación Continua") {
            mecanismo_puntaje = 80;
        }else if (mecanismo[0].meca_nombre == "Participación en Consejo, Mesa, Directorio") {
            mecanismo_puntaje = 93;
        }else if (mecanismo[0].meca_nombre == "Prácticas Profesionales") {
            mecanismo_puntaje = 73;
        }else if (mecanismo[0].meca_nombre == "i+e+TT") {
            mecanismo_puntaje = 100;
        }else if (mecanismo[0].meca_nombre == "AI+E, APT, ATE, AbP") {
            mecanismo_puntaje = 67;
        }
        else {
            /* mecanismo = mecanismo.meca_nombre; */
            mecanismo_puntaje = 0;
        }

        /* console.log(mecanismo); */
        /* Frecuencia */

        frecuencia_txt = "Permanente";
        frecuencia_puntaje = 100;

        /* Resultados */
        if (resultados2 != null) {
          divisor = isNaN(parseInt(resultados2[0].suma_inicial))
            ? 0
            : parseInt(resultados2[0].suma_inicial);
          dividendo = isNaN(parseInt(resultados2[0].suma_final))
            ? 0
            : parseInt(resultados2[0].suma_final);
          if (divisor == 0) {
            resultados_puntaje = 0;
          } else {
            resultado1_aux = Math.round((dividendo / divisor) * 100);
            /* if (resultado1_aux > 100) {
                          resultado1_aux = 100;
                      } */
            resultados_puntaje = resultado1_aux; /* CAMBIAR CON RESULTADO 2 */
          }
        } else {
          resultados_puntaje = 0;
        }
        if (resultados_puntaje > 100) {
          resultados_puntaje = 100;
        }

        /* Cobertura */
        if (cobertura == null) {
          cobertura_puntaje = 0;
        } else {
          dividendo =
            (isNaN(parseInt(cobertura[0].total_docentes_final))
              ? 0
              : parseInt(cobertura[0].total_docentes_final)) +
            (isNaN(parseInt(cobertura[0].total_estudiantes_final))
              ? 0
              : parseInt(cobertura[0].total_estudiantes_final)) +
            (isNaN(parseInt(cobertura[0].total_funcionarios_final))
              ? 0
              : parseInt(cobertura[0].total_funcionarios_final));

          divisor =
            (isNaN(parseInt(cobertura[0].total_docentes))
              ? 0
              : parseInt(cobertura[0].total_docentes)) +
            (isNaN(parseInt(cobertura[0].total_estudiantes))
              ? 0
              : parseInt(cobertura[0].total_estudiantes)) +
            (isNaN(parseInt(cobertura[0].total_funcionarios))
              ? 0
              : parseInt(cobertura[0].total_funcionarios));

          if (coberturaExt != null) {
            dividendo =
              dividendo +
              (isNaN(parseInt(coberturaExt[0].total_externos_final))
                ? 0
                : parseInt(coberturaExt[0].total_externos_final));
            divisor =
              divisor +
              (isNaN(parseInt(coberturaExt[0].total_externos))
                ? 0
                : parseInt(coberturaExt[0].total_externos));
          }

          if (dividendo == null) {
            dividendo = 0;
          }
          if (divisor == null || divisor == 0 || dividendo == 0) {
            cobertura_puntaje = 0;
          } else {
            cobertura_puntaje = Math.round((dividendo / divisor) * 100);
            if (cobertura_puntaje > 100) {
              cobertura_puntaje = 100;
            }
          }
        }

        /* Evaluacion */

        if (evaluacion == null) {
          evaluacion_puntaje = 0;
        } else {
          evaluacion_puntaje = Math.round(
            parseInt(evaluacion.suma_evaluaciones) /
              parseInt(evaluacion.total_evaluaciones)
          );
        }

        indice = Math.round(
          0.2 * mecanismo_puntaje +
            0.1 * frecuencia_puntaje +
            0.25 * resultados_puntaje +
            0.1 * cobertura_puntaje +
            0.35 * evaluacion_puntaje
        );

        if (resultados_puntaje == 0) {
          $("#resultados-nombre").text("Sin Datos");
        } else {
          $("#resultados-nombre").text("");
        }

        if (cobertura_puntaje == 0) {
          $("#cobertura-nombre").text("Sin Datos");
        } else {
          $("#cobertura-nombre").text("");
        }
        if (evaluacion_puntaje == 0) {
          $("#evaluacion-nombre").text("No Evaluada");
        } else {
          $("#evaluacion-nombre").text("");
        }
        /* $('#mecanismo-nombre').text(mecanismo_txt);*/
        $("#frecuencia-nombre").text(frecuencia_txt);
        $("#mecanismo-puntaje").text(mecanismo_puntaje);
        $("#mecanismo-nombre").text(mecanismo[0].meca_nombre);
        $("#frecuencia-puntaje").text(frecuencia_puntaje);
        $("#cobertura-puntaje").text(cobertura_puntaje);
        $("#resultados-puntaje").text(resultados_puntaje);
        $("#evaluacion-puntaje").text(evaluacion_puntaje);
        $("#valor-indice").text(indice);
        $("#codigo_iniciativa").text(inic_codigo);
        $("#modalINVI").modal("show");
        /* console.log(datos.length);
              console.log(cobertura[0]); */
      },
      error: function (error) {
        console.log(datos);
      },
    });
  }

  function calcularIndiceGlobal(inic_codigo) {
    let datos;
    let mecanismo, cobertura, evaluacion;
    let frecuencia_txt;
    let mecanismo_puntaje,
      frecuencia_puntaje,
      cobertura_puntaje,
      resultados_puntaje,
      evaluacion_puntaje;
    let resultado1_aux;
    let divisor, dividendo;
    let indice;

    $.ajax({
      type: "GET",
      url: window.location.origin + "/admin/iniciativa/invi/datos",
      data: {
        iniciativa: inic_codigo,
      },
      success: function (resConsultar) {
        respuesta = JSON.parse(resConsultar);
        datos = respuesta.resultado;

        mecanismo = datos.mecanismo;
        frecuencia = datos.frecuencia;

        cobertura = datos.cobertura;
        coberturaExt = datos.cobertura2;
        resultados2 = datos.resultados2;
        evaluacion = datos.evaluacion;
        resultados_puntaje = 0;

        /* Mecanismo */
        if (mecanismo == null) {
          mecanismo_puntaje = 0;
        }

        mecanismo_puntaje = mecanismo[0].meca_puntaje;

        /* console.log(mecanismo); */
        /* Frecuencia */

        frecuencia_txt = "Permanente";
        frecuencia_puntaje = 100;

        /* Resultados */
        if (resultados2 != null) {
          divisor = isNaN(parseInt(resultados2[0].suma_inicial))
            ? 0
            : parseInt(resultados2[0].suma_inicial);
          dividendo = isNaN(parseInt(resultados2[0].suma_final))
            ? 0
            : parseInt(resultados2[0].suma_final);
          if (divisor == 0) {
            resultados_puntaje = 0;
          } else {
            resultado1_aux = Math.round((dividendo / divisor) * 100);
            /* if (resultado1_aux > 100) {
                          resultado1_aux = 100;
                      } */
            resultados_puntaje = resultado1_aux; /* CAMBIAR CON RESULTADO 2 */
          }
        } else {
          resultados_puntaje = 0;
        }
        if (resultados_puntaje > 100) {
          resultados_puntaje = 100;
        }

        /* Cobertura */
        if (cobertura == null) {
          cobertura_puntaje = 0;
        } else {
          dividendo =
            (isNaN(parseInt(cobertura[0].total_docentes_final))
              ? 0
              : parseInt(cobertura[0].total_docentes_final)) +
            (isNaN(parseInt(cobertura[0].total_estudiantes_final))
              ? 0
              : parseInt(cobertura[0].total_estudiantes_final)) +
            (isNaN(parseInt(cobertura[0].total_funcionarios_final))
              ? 0
              : parseInt(cobertura[0].total_funcionarios_final));

          divisor =
            (isNaN(parseInt(cobertura[0].total_docentes))
              ? 0
              : parseInt(cobertura[0].total_docentes)) +
            (isNaN(parseInt(cobertura[0].total_estudiantes))
              ? 0
              : parseInt(cobertura[0].total_estudiantes)) +
            (isNaN(parseInt(cobertura[0].total_funcionarios))
              ? 0
              : parseInt(cobertura[0].total_funcionarios));

          if (coberturaExt != null) {
            dividendo =
              dividendo +
              (isNaN(parseInt(coberturaExt[0].total_externos_final))
                ? 0
                : parseInt(coberturaExt[0].total_externos_final));
            divisor =
              divisor +
              (isNaN(parseInt(coberturaExt[0].total_externos))
                ? 0
                : parseInt(coberturaExt[0].total_externos));
          }

          if (dividendo == null) {
            dividendo = 0;
          }
          if (divisor == null || divisor == 0 || dividendo == 0) {
            cobertura_puntaje = 0;
          } else {
            cobertura_puntaje = Math.round((dividendo / divisor) * 100);
            if (cobertura_puntaje > 100) {
              cobertura_puntaje = 100;
            }
          }
        }

        /* Evaluacion */

        if (evaluacion == null) {
          evaluacion_puntaje = 0;
        } else {
          evaluacion_puntaje = Math.round(
            parseInt(evaluacion.suma_evaluaciones) /
              parseInt(evaluacion.total_evaluaciones)
          );
        }

        indice = Math.round(
          0.2 * mecanismo_puntaje +
            0.1 * frecuencia_puntaje +
            0.25 * resultados_puntaje +
            0.1 * cobertura_puntaje +
            0.35 * evaluacion_puntaje
        );

        $.ajax({
          type: "POST",
          url: `${window.location.origin}/admin/iniciativa/invi/guardar`,
          dataType: "json",
          headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
          },
          data: {
            mecanismo_nombre: mecanismo[0].meca_nombre,
            mecanismo_puntaje: mecanismo_puntaje,
            frecuencia_nombre: frecuencia_txt,
            frecuencia_puntaje: frecuencia_puntaje,
            resultados_puntaje: resultados_puntaje,
            cobertura_puntaje: cobertura_puntaje,
            evaluacion_puntaje: evaluacion_puntaje,
            valor_indice: indice,
            inic_codigo: inic_codigo,
          },
        });

        /* $('#mecanismo-nombre').text(mecanismo_txt);*/
        // $("#frecuencia-nombre").text(frecuencia_txt);
        // $("#mecanismo-puntaje").text(mecanismo_puntaje);
        // $("#mecanismo-nombre").text(mecanismo[0].meca_nombre);
        // $("#frecuencia-puntaje").text(frecuencia_puntaje);
        // $("#cobertura-puntaje").text(cobertura_puntaje);
        // $("#resultados-puntaje").text(resultados_puntaje);
        // $("#evaluacion-puntaje").text(evaluacion_puntaje);
        // $("#valor-indice").text(indice);
        // $("#codigo_iniciativa").text(inic_codigo);
        /* console.log(datos.length);
              console.log(cobertura[0]); */
      },
      error: function (error) {
        console.log(datos);
      },
    });
  }

  function guardarINVI() {
    $.ajax({
      type: "POST",
      url: `${window.location.origin}/admin/iniciativa/invi/guardar`,
      dataType: "json",
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      data: {
        mecanismo_nombre: $("#mecanismo-nombre").text(),
        mecanismo_puntaje: parseFloat($("#mecanismo-puntaje").text()),
        frecuencia_nombre: $("#frecuencia-nombre").text(),
        frecuencia_puntaje: parseFloat($("#frecuencia-puntaje").text()),
        resultados_puntaje: parseFloat($("#resultados-puntaje").text()),
        cobertura_puntaje: parseFloat($("#cobertura-puntaje").text()),
        evaluacion_puntaje: parseFloat($("#evaluacion-puntaje").text()),
        valor_indice: parseFloat($("#valor-indice").text()),
        inic_codigo: parseInt($("#codigo_iniciativa").text()),
      },
      success: (data) => {
        if (data.state) {
          $("#invi_modal_guardado").html(
            `<div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                <div class="alert-body">
                    <strong>DATOS DE INVI ALMACENADOS</strong>
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
              </div>
        `
          );
        } else {
            console.log(data.error);
          $("#invi_modal_guardado").html(
            `<div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                <div class="alert-body">
                    <strong>ERROR, NO SE PUDIERON GUARDAR LOS DATOS</strong>
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
              </div>
        `
          );
        }
      },
    });
  }
