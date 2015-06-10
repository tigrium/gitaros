# Gitáros mise diás honlapja

Egységek:
- API
- Weblap kliens
- Android kliens

Feladatok felhasználói szemmel:
- Énekek és hozzá tartozó diák listázása szűréssel vagy nélküle
- Új ének és/vagy dia létrehozása
- Énekadatok, diaadatok szerkesztése, képcsere
- Kotta csatolása (legjobb lenne, ha Drive-on keresztül menne - szerver oldal is intézheti elfedve)
- Misesor elmentése, betöltése, módosítása, törlése (jogosultságkezelés)
- Misesor exportálása (web: letöltés zip-ben, android: letöltés vagy küldés emailben)
- Misesor klónozása
- Regisztrálás, belépés, jogosultságkezelés
- Segítségnyújtás új dia készítéséhez

Megvalósításhoz ötletek, elvárások:
- Egy dia több énekhez is tartozhasson
- Diák sorrendje módosítható legyen, egy dia többször is szerepelhessen
 - enekek, diak és valami összekötő tábla (enek_ref, dia_ref, nr - a soszáma a rendezéshez)

- Régi adatbázis átemelése viszonylag automatikusan

Extrák:
- dia készítés
- dátumfigyelés (figyelmeztet nagyböjtben, vagy beállítástól függően ilyenkor alapértelmezett keresésbeállítás)
