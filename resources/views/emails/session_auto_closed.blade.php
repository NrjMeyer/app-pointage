<!DOCTYPE html>
<html>
<body>
<h2>Clôture automatique des sessions</h2>

<p>Les sessions suivantes ont été clôturées automatiquement à {{ now()->format('d/m/Y H:i') }} :</p>

<ul>
    @foreach($sessions as $session)
        <li>
            Employé : {{ $session->utilisateur->UTI_Nom }}
            — Début : {{ $session->WRK_Dte_Heure_Deb }}
            — Fin auto : {{ $session->WRK_Dte_Heure_Fin }}
            — Durée : {{ $session->WRK_Duree_Min }} min
        </li>
    @endforeach
</ul>

<p>Merci,</p>
<p>Le système de pointeuse</p>
</body>
</html>
