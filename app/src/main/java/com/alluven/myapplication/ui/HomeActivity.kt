package com.alluven.myapplication.ui

import android.content.Intent
import android.os.Bundle
import android.view.MenuItem
import android.view.View
import android.widget.Button
import android.widget.ImageView
import android.widget.PopupMenu
import androidx.appcompat.app.AppCompatActivity
import com.alluven.myapplication.R
import com.alluven.myapplication.ui.admin.AdminDashboardActivity
import com.alluven.myapplication.util.SessionManager

class HomeActivity : AppCompatActivity() {

    private lateinit var session: SessionManager

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_home)

        session = SessionManager(this)

        findViewById<Button>(R.id.btnAgendarHero)?.setOnClickListener {
            openLoginOrBooking()
        }

        findViewById<ImageView>(R.id.btnMenu)?.setOnClickListener { v ->
            showTopMenu(v)
        }
    }

    private fun openLoginOrBooking() {
        if (session.isLoggedIn) {
            startActivity(Intent(this, BookingActivity::class.java))
        } else {
            startActivity(Intent(this, LoginActivity::class.java))
        }
    }

    private fun showTopMenu(anchor: View) {
        val popup = PopupMenu(this, anchor)

        val ID_SOBRE   = 1
        val ID_CONTATO = 2
        val ID_BUSCA   = 3
        val ID_LOGIN   = 4
        val ID_DASH    = 5
        val ID_SAIR    = 6

        popup.menu.add(0, ID_SOBRE,   0, "Sobre nós")
        popup.menu.add(0, ID_CONTATO, 1, "Contato")
        popup.menu.add(0, ID_BUSCA,   2, "Buscar salão")

        if (session.isLoggedIn) {
            popup.menu.add(0, ID_DASH, 3, "Meu painel")
            popup.menu.add(0, ID_SAIR, 4, "Sair")
        } else {
            popup.menu.add(0, ID_LOGIN, 3, "Login")
        }

        popup.setOnMenuItemClickListener { item: MenuItem ->
            when (item.itemId) {
                ID_SOBRE -> {
                    startActivity(Intent(this, AboutActivity::class.java))
                    true
                }
                ID_CONTATO -> {

                    val i = Intent(Intent.ACTION_SEND).apply {
                        type = "message/rfc822"
                        putExtra(Intent.EXTRA_EMAIL, arrayOf("contato@seudominio.com"))
                        putExtra(Intent.EXTRA_SUBJECT, "Contato - Alluven")
                    }
                    startActivity(Intent.createChooser(i, "Enviar e-mail"))
                    true
                }
                ID_BUSCA -> {
                    startActivity(Intent(this, SearchActivity::class.java))
                    true
                }
                ID_LOGIN -> {
                    startActivity(Intent(this, LoginActivity::class.java))
                    true
                }
                ID_DASH -> {
                    if (session.role.equals("admin", true)) {
                        startActivity(Intent(this, AdminDashboardActivity::class.java))
                    } else {
                        startActivity(Intent(this, ClientDashboardActivity::class.java))
                    }
                    true
                }
                ID_SAIR -> {
                    session.clear()
                    startActivity(
                        Intent(this, HomeActivity::class.java).addFlags(
                            Intent.FLAG_ACTIVITY_CLEAR_TOP or Intent.FLAG_ACTIVITY_NEW_TASK
                        )
                    )
                    finish()
                    true
                }
                else -> false
            }
        }
        popup.show()
    }
}
