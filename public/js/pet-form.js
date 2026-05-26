/**
 * pet-form.js — Validação do formulário de pet no lado do cliente
 *
 * ATENÇÃO — APENAS EXPERIÊNCIA DO USUÁRIO
 * Este script melhora o feedback visual durante o preenchimento.
 * Ele NÃO substitui nem dispensa a validação do servidor (PetRequest.php).
 * Qualquer dado inválido que passasse por aqui seria rejeitado pelo servidor
 * antes de chegar ao banco de dados. Nunca confie em validação client-side
 * como mecanismo de segurança.
 *
 * Regras espelhadas de: app/Http/Requests/PetRequest.php
 * Se alterar as regras no servidor, lembre de atualizar este arquivo também.
 */
(function () {
  'use strict';

  // SVG do ícone alert_circle — copiado do componente x-icon para uso via JS
  var ICON_ALERTA =
    '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" ' +
    'viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" ' +
    'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
    '<circle cx="12" cy="12" r="9"/><path d="M12 8v4"/><path d="M12 16h.01"/>' +
    '</svg>';

  // ─── Regras de validação ────────────────────────────────────────────────────
  // Espelham as rules() do PetRequest. Mensagens idênticas às do servidor.

  var REGRAS = {
    nome: function (v) {
      if (!v.trim())      return 'O nome do pet é obrigatório.';
      if (v.length > 60)  return 'O nome não pode ter mais de 60 caracteres.';
      // Regex com flag /u para suporte a letras acentuadas (Unicode)
      if (!/^[\p{L}\s'\-]+$/u.test(v))
                          return 'O nome aceita apenas letras, espaços, hífens e apóstrofos.';
      return null;
    },

    especie: function (v) {
      return v ? null : 'Selecione a espécie do pet.';
    },

    sexo: function (v) {
      return v ? null : 'O sexo do pet é obrigatório.';
    },

    // nullable no servidor — só valida se preenchida
    data_nascimento: function (v) {
      if (!v) return null;
      // Comparação por string YYYY-MM-DD evita distorção por fuso horário
      if (v > new Date().toISOString().slice(0, 10))
        return 'A data de nascimento não pode ser no futuro.';
      return null;
    },

    // nullable no servidor — só valida se preenchido
    peso_atual: function (v) {
      if (!v) return null;
      var n = parseFloat(v.replace(',', '.'));
      if (isNaN(n))  return 'O peso deve ser um valor numérico.';
      if (n < 0.01)  return 'O peso mínimo aceito é 0,01 kg.';
      if (n > 200)   return 'O peso máximo aceito é 200,00 kg.';
      return null;
    },

    status: function (v) {
      return v ? null : 'O status do pet é obrigatório.';
    },
  };

  // ─── Helpers de DOM ─────────────────────────────────────────────────────────

  function mostrarErro(campo, mensagem) {
    var wrapper = campo.closest('.pc-field');
    if (!wrapper) return;

    // Ao interagir, descarta o erro que o servidor renderizou para este campo
    // (após resubmissão com falha). O servidor re-renderiza se necessário.
    wrapper.querySelectorAll('.pc-field-error').forEach(function (el) {
      el.remove();
    });

    var classeErro = campo.tagName === 'SELECT'
      ? 'pc-select--error'
      : 'pc-input--error';

    if (mensagem) {
      campo.classList.add(classeErro);

      var div = document.createElement('div');
      div.className = 'pc-field-error pc-field-error--js';
      div.innerHTML = ICON_ALERTA + '<span>' + mensagem + '</span>';

      // Mantém pc-field-help como último filho (ex: texto do microchip)
      var helpEl = wrapper.querySelector('.pc-field-help');
      if (helpEl) {
        wrapper.insertBefore(div, helpEl);
      } else {
        wrapper.appendChild(div);
      }
    } else {
      campo.classList.remove(classeErro);
    }
  }

  function validarCampo(campo) {
    var regra = REGRAS[campo.name];
    if (!regra) return true;
    var erro = regra(campo.value);
    mostrarErro(campo, erro);
    return erro === null;
  }

  // ─── Cálculo de idade em tempo real ─────────────────────────────────────────

  function iniciarCalculoIdade(form) {
    var inputData = form.querySelector('[name="data_nascimento"]');
    if (!inputData) return;

    var display = document.createElement('span');
    display.id = 'pc-idade-display';
    display.setAttribute('aria-live', 'polite');
    display.style.cssText =
      'display:inline-block;font-size:12px;color:var(--pc-primary-700);' +
      'font-weight:600;margin-top:6px;min-height:18px;';

    // Inserido logo após o <input> — antes de erros futuros que usam appendChild
    inputData.insertAdjacentElement('afterend', display);

    function atualizar() {
      var val  = inputData.value;
      var hoje = new Date().toISOString().slice(0, 10);

      if (!val || val > hoje) {
        display.textContent = '';
        return;
      }

      // Sufixo T00:00:00 força meia-noite local, evitando off-by-one de fuso
      var nasc  = new Date(val + 'T00:00:00');
      var agora = new Date();
      var anos  = agora.getFullYear() - nasc.getFullYear();
      var meses = agora.getMonth()    - nasc.getMonth();
      if (agora.getDate() < nasc.getDate()) meses--;
      if (meses < 0) { anos--; meses += 12; }

      var partes = [];
      if (anos  > 0) partes.push(anos  + (anos  === 1 ? ' ano'  : ' anos'));
      if (meses > 0) partes.push(meses + (meses === 1 ? ' mês'  : ' meses'));

      display.textContent = partes.length
        ? '→ ' + partes.join(' e ')
        : '→ menos de 1 mês';
    }

    inputData.addEventListener('change', atualizar);
    inputData.addEventListener('input',  atualizar);
    atualizar(); // calcula imediatamente — útil no formulário de edição
  }

  // ─── Inicialização ───────────────────────────────────────────────────────────

  document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('pet-form');
    if (!form) return;

    iniciarCalculoIdade(form);

    // Registra listeners para cada campo com regra definida
    Object.keys(REGRAS).forEach(function (nome) {
      var campo = form.querySelector('[name="' + nome + '"]');
      if (!campo) return;

      // blur: valida ao sair do campo (principal gatilho)
      campo.addEventListener('blur', function () { validarCampo(campo); });
      // change: cobre selects, checkboxes e date inputs
      campo.addEventListener('change', function () { validarCampo(campo); });
      // input: feedback em tempo real para campos de texto livre
      if (campo.tagName === 'INPUT' && campo.type === 'text') {
        campo.addEventListener('input', function () { validarCampo(campo); });
      }
    });

    // Valida tudo no submit — impede envio se houver campo inválido
    form.addEventListener('submit', function (e) {
      var valido = true;
      Object.keys(REGRAS).forEach(function (nome) {
        var campo = form.querySelector('[name="' + nome + '"]');
        if (campo && !validarCampo(campo)) valido = false;
      });

      if (!valido) {
        e.preventDefault();
        // Rola até o primeiro erro visível
        var primeiroErro = form.querySelector('.pc-field-error--js');
        if (primeiroErro) {
          primeiroErro.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }
    });
  });
})();
