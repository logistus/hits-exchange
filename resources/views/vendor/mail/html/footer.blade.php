<tr>
  <td>
    <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
      <tr>
        <td class="content-cell" align="center">
          {{ Illuminate\Mail\Markdown::parse($slot) }}
          <p>You can update your <a href="{{ url('user/profile') }}">email notification preferences</a> at any time.</p>
        </td>
      </tr>
    </table>
  </td>
</tr>
